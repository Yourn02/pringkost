<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\User;
use App\Models\Facility;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 8 Statistik Utama Dinamis dari Database (Pengguna = Pemilik + Pencari, role admin dikecualikan)
        $totalUsers = User::whereIn('role', ['pemilik', 'pencari'])->count();
        $totalOwners = User::where('role', 'pemilik')->count();
        $totalSearchers = User::where('role', 'pencari')->count();
        $totalKosts = Kost::count();
        $pendingKostsCount = Kost::where('status', 'pending')->count();
        $approvedKostsCount = Kost::where('status', 'approved')->count();
        $rejectedKostsCount = Kost::where('status', 'rejected')->count();
        $totalFacilities = Facility::count();

        // 5 Pengajuan kost terbaru
        $recentPendingKosts = Kost::with('user', 'images')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Aktivitas terbaru: Booking terbaru & Kost approved terbaru
        $recentBookings = Booking::with('user', 'kost')
            ->latest()
            ->take(5)
            ->get();

        $recentApprovedKosts = Kost::with('user')
            ->where('status', 'approved')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOwners',
            'totalSearchers',
            'totalKosts',
            'pendingKostsCount',
            'approvedKostsCount',
            'rejectedKostsCount',
            'totalFacilities',
            'recentPendingKosts',
            'recentBookings',
            'recentApprovedKosts'
        ));
    }
}
