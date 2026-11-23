<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Total Kost milik pemilik
        $totalKosts = Kost::where('user_id', $userId)->count();

        // 2. Kost Menunggu Verifikasi
        $pendingKosts = Kost::where('user_id', $userId)->where('status', 'pending')->count();

        // Ringkasan status kost
        $approvedKosts = Kost::where('user_id', $userId)->where('status', 'approved')->count();
        $rejectedKosts = Kost::where('user_id', $userId)->where('status', 'rejected')->count();

        // 3. Pesanan Masuk (pending)
        $incomingBookings = Booking::whereHas('kost', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'pending')->count();

        // Total seluruh booking milik pemilik
        $totalBookings = Booking::whereHas('kost', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        // 4. Kamar Tersedia & Total Kamar
        $availableRooms = Kost::where('user_id', $userId)->sum('available_rooms');
        $totalRooms = Kost::where('user_id', $userId)->sum('total_rooms');

        // 5 Pesanan Terbaru
        $recentBookings = Booking::whereHas('kost', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['user', 'kost.images'])
          ->latest()
          ->take(5)
          ->get();

        // Properti Terbaru (maksimal 4)
        $recentKosts = Kost::where('user_id', $userId)
            ->with(['images', 'facilities'])
            ->latest()
            ->take(4)
            ->get();

        return view('owner.dashboard', compact(
            'totalKosts',
            'pendingKosts',
            'approvedKosts',
            'rejectedKosts',
            'incomingBookings',
            'totalBookings',
            'availableRooms',
            'totalRooms',
            'recentBookings',
            'recentKosts'
        ));
    }
}
