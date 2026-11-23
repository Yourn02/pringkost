<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use Illuminate\Http\Request;

class AdminKostController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $type = $request->query('type');
        $search = $request->query('search');

        $query = Kost::with(['user', 'images', 'facilities'])->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($type && in_array($type, ['putra', 'putri', 'campur'])) {
            $query->where('type', $type);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $kosts = $query->paginate(10)->withQueryString();

        $counts = [
            'all' => Kost::count(),
            'pending' => Kost::where('status', 'pending')->count(),
            'approved' => Kost::where('status', 'approved')->count(),
            'rejected' => Kost::where('status', 'rejected')->count(),
        ];

        return view('admin.kosts.index', compact('kosts', 'counts', 'status', 'type', 'search'));
    }

    public function show($id)
    {
        $kost = Kost::with(['user', 'images', 'facilities', 'bookings'])->findOrFail($id);
        return view('admin.kosts.show', compact('kost'));
    }

    public function approve($id)
    {
        $kost = Kost::findOrFail($id);
        $kost->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        if ($kost->user) {
            $kost->user->notify(new \App\Notifications\GeneralNotification([
                'title' => 'Properti Kost Disetujui!',
                'message' => "Properti \"{$kost->name}\" telah diverifikasi & disetujui oleh Administrator. Kini tampil di katalog publik.",
                'icon' => 'fa-circle-check',
                'type' => 'success',
                'url' => route('owner.kosts.show', $kost->id),
            ]));
        }

        return redirect()->back()->with('success', "Listing kost \"{$kost->name}\" berhasil disetujui dan kini aktif.");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $kost = Kost::findOrFail($id);
        $reason = $request->input('rejection_reason', 'Pengajuan tidak memenuhi syarat kelayakan.');
        $kost->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        if ($kost->user) {
            $kost->user->notify(new \App\Notifications\GeneralNotification([
                'title' => 'Properti Kost Ditolak',
                'message' => "Pengajuan properti \"{$kost->name}\" ditolak: {$reason}. Silakan perbaiki data properti Anda.",
                'icon' => 'fa-circle-xmark',
                'type' => 'danger',
                'url' => route('owner.kosts.edit', $kost->id),
            ]));
        }

        return redirect()->back()->with('success', "Listing kost \"{$kost->name}\" telah ditolak.");
    }
}
