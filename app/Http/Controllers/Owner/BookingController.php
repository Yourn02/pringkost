<?php

namespace App\Http\Controllers\Owner;

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

        $query = Booking::whereHas('kost', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['user', 'kost.images'])->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                })->orWhereHas('kost', function($k) use ($search) {
                    $k->where('name', 'like', "%{$search}%");
                });
            });
        }

        $bookings = $query->paginate(10)->withQueryString();

        $counts = [
            'all' => Booking::whereHas('kost', fn($q) => $q->where('user_id', $userId))->count(),
            'pending' => Booking::whereHas('kost', fn($q) => $q->where('user_id', $userId))->where('status', 'pending')->count(),
            'approved' => Booking::whereHas('kost', fn($q) => $q->where('user_id', $userId))->where('status', 'approved')->count(),
            'rejected' => Booking::whereHas('kost', fn($q) => $q->where('user_id', $userId))->where('status', 'rejected')->count(),
        ];

        return view('owner.bookings.index', compact('bookings', 'counts', 'status', 'search'));
    }

    public function show($id)
    {
        $userId = Auth::id();
        $booking = Booking::whereHas('kost', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['user', 'kost.images', 'kost.facilities'])->findOrFail($id);

        return view('owner.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $userId = Auth::id();

        // Database transaction untuk memastikan konsistensi sisa kamar & status booking
        return DB::transaction(function () use ($request, $id, $userId) {
            $booking = Booking::whereHas('kost', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->with('kost', 'user')->lockForUpdate()->findOrFail($id);

            $oldStatus = $booking->status;
            $newStatus = $request->status;

            if ($oldStatus === $newStatus) {
                return redirect()->back()->with('info', "Status pesanan sudah dalam kondisi " . ucfirst($newStatus) . ".");
            }

            $kost = $booking->kost;

            if ($newStatus === 'approved') {
                if ($kost->available_rooms <= 0) {
                    return redirect()->back()->with('error', "Gagal menyetujui pesanan! Kapasitas kamar kost \"{$kost->name}\" sudah habis (0 kamar tersedia).");
                }

                // Kurangi kamar jika sebelumnya bukan approved
                if ($oldStatus !== 'approved') {
                    $kost->decrement('available_rooms');
                }
            } elseif ($newStatus === 'rejected') {
                // Jika sebelumnya pernah disetujui, kembalikan kuota kamar
                if ($oldStatus === 'approved' && $kost->available_rooms < $kost->total_rooms) {
                    $kost->increment('available_rooms');
                }
            }

            $booking->status = $newStatus;
            $booking->save();

            // Kirim notifikasi ke mahasiswa / pencari kost jika ada
            if ($booking->user) {
                $title = $newStatus === 'approved' ? 'Pengajuan Sewa Disetujui' : 'Pengajuan Sewa Ditolak';
                $message = "Pengajuan sewa Anda untuk {$kost->name} telah " . ($newStatus === 'approved' ? 'disetujui' : 'ditolak') . " oleh pemilik kost.";
                $booking->user->notify(new GeneralNotification([
                    'title' => $title,
                    'message' => $message,
                    'icon' => $newStatus === 'approved' ? 'fa-circle-check' : 'fa-circle-xmark',
                    'type' => $newStatus === 'approved' ? 'success' : 'danger',
                    'url' => route('booking.index'),
                ]));
            }

            $label = $newStatus === 'approved' ? 'disetujui' : 'ditolak';
            return redirect()->back()->with('success', "Pesanan dari {$booking->user->name} berhasil {$label}.");
        });
    }
}
