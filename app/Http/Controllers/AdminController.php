<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard Admin: Menampilkan statistik, daftar kost verifikasi, dan data master
    public function index()
    {
        // Statistik untuk widget dashboard
        $totalUsers = User::count();
        $totalKosts = Kost::count();
        $pendingKosts = Kost::where('status', 'pending')->latest()->get();
        $approvedKosts = Kost::where('status', 'approved')->count();
        
        $allKosts = Kost::latest()->get();
        $facilities = Facility::latest()->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalKosts', 
            'pendingKosts', 
            'approvedKosts', 
            'allKosts', 
            'facilities'
        ));
    }

    // Setujui (Approve) Listing Kost
    public function approve($id)
    {
        $kost = Kost::findOrFail($id);
        $kost->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Kost berhasil disetujui dan kini tampil secara publik.');
    }

    // Tolak (Reject) Listing Kost
    public function reject($id)
    {
        $kost = Kost::findOrFail($id);
        $kost->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Kost berhasil ditolak.');
    }

    // Tambah Fasilitas Master Baru
    public function storeFacility(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:facilities']);
        
        Facility::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    // Hapus Fasilitas Master
    public function destroyFacility($id)
    {
        Facility::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Fasilitas berhasil dihapus.');
    }
}