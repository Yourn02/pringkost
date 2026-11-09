<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kost;
use App\Models\Facility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OwnerController extends Controller
{
    // Menampilkan halaman dashboard pemilik kost
    public function dashboard()
    {
        // Mengambil data kost yang hanya didaftarkan oleh user yang sedang login
        $kosts = Kost::where('user_id', Auth::id())->latest()->get();
        return view('owner.dashboard', compact('kosts'));
    }

    // Menampilkan form tambah kost
    public function create()
    {
        $facilities = Facility::all();
        return view('owner.create', compact('facilities'));
    }

    // Menyimpan data kost baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:putra,putri,campur',
            'price' => 'required|numeric',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'total_rooms' => 'required|integer',
            'available_rooms' => 'required|integer',
        ]);

        $kost = Kost::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'price' => $request->price,
            'address' => $request->address,
            'description' => $request->description,
            'total_rooms' => $request->total_rooms,
            'available_rooms' => $request->available_rooms,
            'status' => 'pending', // Status awal menunggu persetujuan Admin
        ]);

        // Jika ada fasilitas yang dipilih
        if ($request->has('facilities')) {
            $kost->facilities()->attach($request->facilities);
        }

        return redirect()->route('owner.dashboard')->with('success', 'Kost berhasil diajukan dan menunggu verifikasi Admin.');
    }
}