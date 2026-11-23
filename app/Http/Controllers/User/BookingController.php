<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kost;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Booking::where('user_id', $userId)
            ->with(['kost.images', 'kost.user'])
            ->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('kost', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(10)->withQueryString();

        $counts = [
            'all' => Booking::where('user_id', $userId)->count(),
            'pending' => Booking::where('user_id', $userId)->where('status', 'pending')->count(),
            'approved' => Booking::where('user_id', $userId)->where('status', 'approved')->count(),
            'rejected' => Booking::where('user_id', $userId)->where('status', 'rejected')->count(),
        ];

        return view('user.bookings.index', compact('bookings', 'counts', 'status', 'search'));
    }

    public function show($id)
    {
        $userId = Auth::id();
        $booking = Booking::where('user_id', $userId)
            ->with(['kost.images', 'kost.facilities', 'kost.user'])
            ->findOrFail($id);

        return view('user.bookings.show', compact('booking'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'duration_months' => 'required|integer|min:1|max:36',
        ], [
            'start_date.required' => 'Pilih tanggal mulai sewa.',
            'start_date.after_or_equal' => 'Tanggal mulai sewa minimal hari ini.',
            'duration_months.required' => 'Tentukan durasi sewa.',
            'duration_months.min' => 'Durasi sewa minimal 1 bulan.',
        ]);

        $kost = Kost::where('status', 'approved')->findOrFail($id);

        if ($kost->available_rooms <= 0) {
            return redirect()->back()->with('error', "Maaf, seluruh kamar pada {$kost->name} saat ini sudah penuh terisi.");
        }

        $totalPrice = $kost->price * $request->duration_months;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'kost_id' => $kost->id,
            'start_date' => $request->start_date,
            'duration_months' => $request->duration_months,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Kirim notifikasi ke pemilik kost
        if ($kost->user) {
            $kost->user->notify(new GeneralNotification([
                'title' => 'Pengajuan Sewa Baru Masuk',
                'message' => Auth::user()->name . " mengajukan sewa untuk {$kost->name} selama {$request->duration_months} bulan.",
                'icon' => 'fa-calendar-check',
                'type' => 'info',
                'url' => route('owner.bookings.show', $booking->id),
            ]));
        }

        return redirect()->route('user.bookings.index')->with('success', "Pengajuan sewa untuk {$kost->name} berhasil dikirim! Mohon tunggu konfirmasi dari pemilik kost.");
    }

    public function cancel($id)
    {
        $userId = Auth::id();

        // Hanya booking dengan status 'pending' yang dapat dibatalkan oleh pengguna
        $booking = Booking::where('user_id', $userId)
            ->where('status', 'pending')
            ->with('kost.user')
            ->findOrFail($id);

        $booking->delete();

        // Notifikasi ke pemilik kost jika ada
        if ($booking->kost && $booking->kost->user) {
            $booking->kost->user->notify(new GeneralNotification([
                'title' => 'Pengajuan Sewa Dibatalkan',
                'message' => Auth::user()->name . " membatalkan pengajuan sewa untuk {$booking->kost->name}.",
                'icon' => 'fa-calendar-xmark',
                'type' => 'warning',
                'url' => route('owner.bookings.index'),
            ]));
        }

        return redirect()->route('user.bookings.index')->with('success', 'Pengajuan sewa berhasil dibatalkan.');
    }
}
