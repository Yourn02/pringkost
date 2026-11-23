<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Menampilkan daftar kost favorit user
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->with('kost.images')->latest()->get();
        return view('wishlist.index', compact('wishlists'));
    }

    // Menambah atau menghapus dari wishlist (Toggle)
    public function toggle($kostId)
    {
        $userId = Auth::id();

        $wishlist = Wishlist::where('user_id', $userId)->where('kost_id', $kostId)->first();

        if ($wishlist) {
            $wishlist->delete();
            return redirect()->back()->with('success', 'Kost berhasil dihapus dari daftar favorit.');
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'kost_id' => $kostId,
            ]);
            return redirect()->back()->with('success', 'Kost berhasil ditambahkan ke daftar favorit!');
        }
    }
}