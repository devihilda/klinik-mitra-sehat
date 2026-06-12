<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Polyclinic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PolyclinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $polyclinics = Polyclinic::latest()->get();

        return view('officers.polyclinics.index', compact('polyclinics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('officers.polyclinics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * Kebutuhan Praktikum (Unrestricted File Upload):
     * - Sengaja tidak ada validasi tipe file / ekstensi berkas (mimes).
     * - Menggunakan $file->getClientOriginalName() untuk mempertahankan nama dan ekstensi asli.
     * - Disimpan di direktori publik (public/uploads/poli) sehingga dapat diakses langsung oleh attacker untuk RCE.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/poli'), $filename);
            $data['image_path'] = 'uploads/poli/'.$filename;
        }

        Polyclinic::create($data);

        return redirect()->route('polyclinics.index')->with('success', 'Poli berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $polyclinic = Polyclinic::findOrFail($id);

        return view('officers.polyclinics.show', compact('polyclinic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $polyclinic = Polyclinic::findOrFail($id);

        return view('officers.polyclinics.edit', compact('polyclinic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Kebutuhan Praktikum (Unrestricted File Upload):
     * - Sengaja tidak ada validasi tipe file / ekstensi berkas (mimes) pada update.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $polyclinic = Polyclinic::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/poli'), $filename);

            // Hapus gambar lama jika ada
            if ($polyclinic->image_path && file_exists(public_path($polyclinic->image_path))) {
                @unlink(public_path($polyclinic->image_path));
            }

            $data['image_path'] = 'uploads/poli/'.$filename;
        }

        $polyclinic->update($data);

        return redirect()->route('polyclinics.index')->with('success', 'Poli berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $polyclinic = Polyclinic::findOrFail($id);

        if ($polyclinic->image_path && file_exists(public_path($polyclinic->image_path))) {
            @unlink(public_path($polyclinic->image_path));
        }

        $polyclinic->delete();

        return redirect()->route('polyclinics.index')->with('success', 'Poli berhasil dihapus.');
    }
}
