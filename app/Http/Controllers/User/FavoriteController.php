<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = Wishlist::where('user_id', $userId)
            ->whereHas('kost', function($q) {
                $q->where('status', 'approved');
            })
            ->with(['kost.images', 'kost.facilities'])
            ->latest();

        $wishlists = $query->paginate(9)->withQueryString();

        return view('user.favorites.index', compact('wishlists'));
    }

    public function toggle($kostId)
    {
        $userId = Auth::id();

        // Cek apakah kost ada dan berstatus approved
        $kost = Kost::where('status', 'approved')->findOrFail($kostId);

        $wishlist = Wishlist::where('user_id', $userId)->where('kost_id', $kostId)->first();

        if ($wishlist) {
            $wishlist->delete();
            return redirect()->back()->with('success', "\"{$kost->name}\" berhasil dihapus dari daftar favorit.");
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'kost_id' => $kostId,
            ]);
            return redirect()->back()->with('success', "\"{$kost->name}\" berhasil ditambahkan ke daftar favorit!");
        }
    }

    public function destroy($kostId)
    {
        $userId = Auth::id();
        $wishlist = Wishlist::where('user_id', $userId)->where('kost_id', $kostId)->firstOrFail();
        $wishlist->delete();

        return redirect()->back()->with('success', 'Kost berhasil dihapus dari daftar favorit.');
    }
}
