<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $status = $request->query('status');

        // Sesuai kebutuhan: hanya menampilkan pengguna reguler (pemilik dan pencari). Role admin tidak ditampilkan.
        $query = User::whereIn('role', ['pemilik', 'pencari'])
            ->withCount(['kosts', 'bookings'])
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role && in_array($role, ['pemilik', 'pencari'])) {
            $query->where('role', $role);
        }

        if ($status && in_array($status, ['active', 'suspended', 'blocked'])) {
            $query->where('status', $status);
        }

        $users = $query->paginate(10)->withQueryString();

        $counts = [
            'all' => User::whereIn('role', ['pemilik', 'pencari'])->count(),
            'pemilik' => User::where('role', 'pemilik')->count(),
            'pencari' => User::where('role', 'pencari')->count(),
        ];

        return view('admin.users.index', compact('users', 'counts', 'search', 'role', 'status'));
    }

    public function show($id)
    {
        // Hanya izinkan melihat pengguna non-admin (pemilik & pencari)
        $user = User::whereIn('role', ['pemilik', 'pencari'])
            ->with(['kosts', 'bookings.kost'])
            ->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,blocked',
        ]);

        $user = User::findOrFail($id);
        $currentAdmin = Auth::user();

        // Keamanan: Lindungi akun admin dan akun yang sedang login
        if ($user->id === $currentAdmin->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri.');
        }

        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Akun Administrator tidak dapat diubah statusnya melalui menu ini.');
        }

        $user->update(['status' => $request->status]);

        $label = match ($request->status) {
            'active' => 'diaktifkan kembali',
            'suspended' => 'ditangguhkan (suspended)',
            'blocked' => 'diblokir (blocked)',
        };

        return redirect()->back()->with('success', "Akun \"{$user->name}\" ({$user->email}) berhasil {$label}.");
    }
}
