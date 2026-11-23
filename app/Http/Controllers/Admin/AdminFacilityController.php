<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class AdminFacilityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Facility::withCount('kosts')->oldest();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $facilities = $query->paginate(10)->withQueryString();

        return view('admin.facilities.index', compact('facilities', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:facilities,name',
        ], [
            'name.required' => 'Nama fasilitas wajib diisi.',
            'name.unique' => 'Fasilitas dengan nama tersebut sudah ada.',
        ]);

        Facility::create($validated);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $facility = Facility::withCount('kosts')->findOrFail($id);
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:facilities,name,' . $facility->id,
        ], [
            'name.required' => 'Nama fasilitas wajib diisi.',
            'name.unique' => 'Fasilitas dengan nama tersebut sudah ada.',
        ]);

        $facility->update($validated);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $facility = Facility::withCount('kosts')->findOrFail($id);

        // Validasi relasi: jika fasilitas masih digunakan kost, jangan hapus sembarangan
        if ($facility->kosts_count > 0) {
            return redirect()->back()->with('error', "Fasilitas \"{$facility->name}\" tidak dapat dihapus karena masih digunakan oleh {$facility->kosts_count} listing kost.");
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('success', "Fasilitas \"{$facility->name}\" berhasil dihapus.");
    }
}
