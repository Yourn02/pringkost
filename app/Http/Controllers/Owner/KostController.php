<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Facility;
use App\Models\KostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KostController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = in_array((int)$request->query('per_page', 10), [5, 10, 25, 50]) ? (int)$request->query('per_page', 10) : 10;

        $query = Kost::where('user_id', $userId)->with(['images', 'facilities', 'bookings'])->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $kosts = $query->paginate($perPage)->withQueryString();

        $counts = [
            'all' => Kost::where('user_id', $userId)->count(),
            'approved' => Kost::where('user_id', $userId)->where('status', 'approved')->count(),
            'pending' => Kost::where('user_id', $userId)->where('status', 'pending')->count(),
            'rejected' => Kost::where('user_id', $userId)->where('status', 'rejected')->count(),
        ];

        return view('owner.kosts.index', compact('kosts', 'counts', 'search', 'status', 'perPage'));
    }

    public function create()
    {
        $facilities = Facility::all();
        return view('owner.kosts.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:putra,putri,campur',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string',
            'description' => 'required|string',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0|lte:total_rooms',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'available_rooms.lte' => 'Kamar tersedia tidak boleh melebihi total seluruh kamar.',
        ]);

        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug . '-' . Str::random(5);

        $kost = Kost::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => $slug,
            'type' => $request->type,
            'price' => $request->price,
            'address' => $request->address,
            'description' => $request->description,
            'total_rooms' => $request->total_rooms,
            'available_rooms' => $request->available_rooms,
            'status' => 'pending', // Menunggu persetujuan Admin
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'rejection_reason' => null,
        ]);

        if ($request->has('facilities')) {
            $kost->facilities()->sync($request->facilities);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('kosts', 'public');
                KostImage::create([
                    'kost_id' => $kost->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('owner.kosts.index')->with('success', 'Properti kost baru berhasil diajukan! Menunggu peninjauan dan verifikasi dari Administrator.');
    }

    public function show($id)
    {
        $kost = Kost::where('user_id', Auth::id())
            ->with(['images', 'facilities', 'bookings.user'])
            ->findOrFail($id);

        return view('owner.kosts.show', compact('kost'));
    }

    public function edit($id)
    {
        $kost = Kost::where('user_id', Auth::id())
            ->with(['images', 'facilities'])
            ->findOrFail($id);

        $facilities = Facility::all();

        return view('owner.kosts.edit', compact('kost', 'facilities'));
    }

    public function update(Request $request, $id)
    {
        $kost = Kost::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:putra,putri,campur',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string',
            'description' => 'required|string',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0|lte:total_rooms',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'available_rooms.lte' => 'Kamar tersedia tidak boleh melebihi total seluruh kamar.',
        ]);

        // Jika kost sebelumnya ditolak, setelah diperbaiki otomatis kembali ke status 'pending' untuk diverifikasi ulang
        $newStatus = $kost->status === 'rejected' ? 'pending' : $kost->status;
        $rejectionReason = $kost->status === 'rejected' ? null : $kost->rejection_reason;

        $kost->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'address' => $request->address,
            'description' => $request->description,
            'total_rooms' => $request->total_rooms,
            'available_rooms' => $request->available_rooms,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $newStatus,
            'rejection_reason' => $rejectionReason,
        ]);

        if ($request->has('facilities')) {
            $kost->facilities()->sync($request->facilities);
        } else {
            $kost->facilities()->detach();
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('kosts', 'public');
                KostImage::create([
                    'kost_id' => $kost->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('owner.kosts.index')->with('success', 'Data properti kost berhasil diperbarui' . ($newStatus === 'pending' && $kost->getOriginal('status') === 'rejected' ? ' dan diajukan ulang untuk verifikasi admin.' : '.'));
    }

    public function destroy($id)
    {
        $kost = Kost::where('user_id', Auth::id())->withCount('bookings')->findOrFail($id);

        // Keamanan: Cek apakah ada booking aktif (pending atau approved)
        $activeBookingsCount = $kost->bookings()->whereIn('status', ['pending', 'approved'])->count();
        if ($activeBookingsCount > 0) {
            return redirect()->back()->with('error', "Properti kost \"{$kost->name}\" tidak dapat dihapus karena masih memiliki {$activeBookingsCount} pesanan aktif (Disetujui/Pending). Selesaikan pesanan terlebih dahulu.");
        }

        // Hapus file gambar dari storage
        foreach ($kost->images as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }

        $kost->facilities()->detach();
        $kost->delete();

        return redirect()->route('owner.kosts.index')->with('success', "Properti \"{$kost->name}\" berhasil dihapus.");
    }

    public function deleteImage($id)
    {
        $image = KostImage::whereHas('kost', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()->back()->with('success', 'Foto berhasil dihapus.');
    }

    public function storeImages(Request $request, Kost $kost)
    {
        // Pastikan hanya pemilik kost tersebut yang bisa mengedit
        if ($kost->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kost ini.');
        }

        $request->validate([
            'images'   => 'required|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('kosts', 'public');
            KostImage::create([
                'kost_id'    => $kost->id,
                'image_path' => $path,
            ]);
        }

        return back()->with('success', 'Foto kost berhasil ditambahkan.');
    }

    public function destroyImage(KostImage $image)
    {
        $kost = $image->kost;

        // Pastikan hanya pemilik kost yang bisa menghapus foto
        if (!$kost || $kost->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus foto ini.');
        }

        // Hapus file fisik di storage jika ada
        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'Foto kost berhasil dihapus.');
    }
}