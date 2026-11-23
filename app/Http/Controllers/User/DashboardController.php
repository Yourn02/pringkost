<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Booking;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Statistik Pengguna
        $favoriteCount = Wishlist::where('user_id', $userId)->count();
        $totalBookings = Booking::where('user_id', $userId)->count();
        $pendingBookings = Booking::where('user_id', $userId)->where('status', 'pending')->count();
        $approvedBookings = Booking::where('user_id', $userId)->where('status', 'approved')->count();

        // 2. Rekomendasi Kost (Kost Approved terbaru dengan kamar tersedia > 0)
        $recommendedKosts = Kost::where('status', 'approved')
            ->where('available_rooms', '>', 0)
            ->with(['images', 'facilities'])
            ->latest()
            ->take(6)
            ->get();

        // ID kost yang difavoritkan pengguna untuk icon toggle
        $userWishlistIds = Wishlist::where('user_id', $userId)->pluck('kost_id')->toArray();

        // 3. Aktivitas Pemesanan Terbaru (Maksimal 5)
        $recentBookings = Booking::where('user_id', $userId)
            ->with(['kost.images'])
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'favoriteCount',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'recommendedKosts',
            'userWishlistIds',
            'recentBookings'
        ));
    }
}
