<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Kost;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request, $id)
    {

    $user = Auth::user();
    
        if (Auth::check() && $user && $user->role === 'admin') {
    return back()->with('error', 'Akun admin tidak dapat mengajukan booking kamar.');
}

        // Validasi input dari form
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'duration_months' => 'required|integer|min:1',
        ]);

        $kost = Kost::findOrFail($id);

        // Hitung total harga otomatis (Harga per bulan * Durasi)
        $totalPrice = $kost->price * $request->duration_months;

        // Simpan data booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'kost_id' => $kost->id,
            'start_date' => $request->start_date,
            'duration_months' => $request->duration_months,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Notifikasi ke pemilik kost jika ada
        if ($kost->user) {
            $kost->user->notify(new \App\Notifications\GeneralNotification([
                'title' => 'Pesanan Sewa Baru Masuk!',
                'message' => Auth::user()->name . " mengajukan pemesanan untuk \"{$kost->name}\" selama {$request->duration_months} bulan.",
                'icon' => 'fa-calendar-check',
                'type' => 'info',
                'url' => route('owner.bookings.index'),
            ]));
        }

        return redirect()->back()->with('success', 'Pengajuan sewa berhasil dikirim! Silakan tunggu konfirmasi.');
    }
    
    public function index()
{
    $bookings = Auth::user()->bookings()->with('kost')->latest()->get();
    return view('booking.index', compact('bookings'));
}

    // Menampilkan daftar pesanan untuk pemilik kost
    public function ownerIndex()
    {
        // Ambil semua booking yang kost-nya dimiliki oleh user yang sedang login
        $bookings = Booking::whereHas('kost', function($query) {
            $query->where('user_id', Auth::id());
        })->with(['user', 'kost'])->latest()->get();

        return view('owner.bookings', compact('bookings'));
    }

    // Mengubah status booking (approved / rejected)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $booking = Booking::with('kost')->findOrFail($id);

        // Pastikan yang mengubah adalah pemilik kost yang bersangkutan
        if ($booking->kost->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->status = $request->status;
        $booking->save();

        // Jika disetujui, opsional: kurangi sisa kamar
        if ($request->status == 'approved') {
            $kost = $booking->kost;
            if ($kost->available_rooms > 0) {
                $kost->available_rooms -= 1;
                $kost->save();
            }
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui menjadi ' . $request->status);
    }
    
}