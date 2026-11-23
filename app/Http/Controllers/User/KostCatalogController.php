<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Facility;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KostCatalogController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Hanya kost yang approved
        $query = Kost::where('status', 'approved')->with(['images', 'facilities', 'user']);

        // 1. Search nama atau alamat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // 2. Filter tipe kost
        if ($request->filled('type') && in_array($request->type, ['putra', 'putri', 'campur'])) {
            $query->where('type', $request->type);
        }

        // 3. Filter rentang harga
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // 4. Filter fasilitas
        if ($request->filled('facility_id')) {
            $facilityId = $request->facility_id;
            $query->whereHas('facilities', function($q) use ($facilityId) {
                $q->where('facilities.id', $facilityId);
            });
        }

        // 5. Sorting
        if ($request->filled('sort')) {
            if ($request->sort === 'price_low') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'price_high') {
                $query->orderBy('price', 'desc');
            } elseif ($request->sort === 'rooms_available') {
                $query->orderBy('available_rooms', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        // 6. Pagination & Per Page
        $perPage = in_array((int)$request->query('per_page', 9), [6, 9, 12, 24]) ? (int)$request->query('per_page', 9) : 9;
        $kosts = $query->paginate($perPage)->withQueryString();

        // Master Fasilitas untuk filter
        $facilities = Facility::all();

        // Wishlist pengguna saat ini
        $userWishlistIds = $userId ? Wishlist::where('user_id', $userId)->pluck('kost_id')->toArray() : [];

        return view('user.kosts.index', compact('kosts', 'facilities', 'userWishlistIds', 'perPage'));
    }

    public function show($id)
    {
        $kost = Kost::where('status', 'approved')
            ->with(['images', 'facilities', 'user'])
            ->findOrFail($id);

        $userId = Auth::id();
        $isFavorited = $userId ? Wishlist::where('user_id', $userId)->where('kost_id', $kost->id)->exists() : false;

        return view('user.kosts.show', compact('kost', 'isFavorited'));
    }
}
