<?php

// namespace App\Models;
namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Facility;
use Illuminate\Http\Request;

class KostController extends Controller
{
    public function index(Request $request)
    {
        // Hanya ambil kost yang sudah disetujui oleh admin
        $query = Kost::where('status', 'approved')->with(['images', 'facilities']);

        // 1. Pencarian berdasarkan Nama Kost atau Alamat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // 2. Filter berdasarkan Tipe Kost (putra / putri / campur)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 3. Filter berdasarkan Rentang Harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 4. Sorting / Pengurutan Harga & Terbaru
        if ($request->filled('sort')) {
            if ($request->sort == 'price_low') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'price_high') {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        // Ambil data dengan pagination (9 item per halaman) agar tetap rapi
        $kosts = $query->paginate(9)->withQueryString();

        return view('kost.index', compact('kosts'));
    }

    // Fungsi detail kost yang sudah kita buat sebelumnya
    public function show($id)
    {
        $kost = Kost::with(['images', 'facilities', 'user'])->where('status', 'approved')->findOrFail($id);
        return view('kost.show', compact('kost'));
    }
}