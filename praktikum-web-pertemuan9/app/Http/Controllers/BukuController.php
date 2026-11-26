<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('buku.index', [
            'bukus' => Buku::with('kategori')->orderBy('id')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('buku.form', [
            'buku' => new Buku,
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode' => ['required', 'max:10', 'unique:bukus,kode'],
            'judul' => ['required', 'max:255'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
        ], [
            'kode.required' => 'Kode buku wajib diisi.',
            'kode.max' => 'Kode buku maksimal 10 karakter.',
            'kode.unique' => 'Kode buku sudah digunakan.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.max' => 'Judul buku maksimal 255 karakter.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
        ]);

        Buku::create($data);

        return redirect()->route('buku.index')->with('status', 'Buku ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $buku): View
    {
        return view('buku.form', [
            'buku' => $buku,
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Buku $buku): RedirectResponse
    {
        $data = $request->validate([
            'kode' => ['required', 'max:10', Rule::unique('bukus', 'kode')->ignore($buku->id)],
            'judul' => ['required', 'max:255'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
        ], [
            'kode.required' => 'Kode buku wajib diisi.',
            'kode.max' => 'Kode buku maksimal 10 karakter.',
            'kode.unique' => 'Kode buku sudah digunakan.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.max' => 'Judul buku maksimal 255 karakter.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
        ]);

        $buku->update($data);

        return redirect()->route('buku.index')->with('status', 'Buku diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buku $buku): RedirectResponse
    {
        $buku->delete();

        return redirect()->route('buku.index')->with('status', 'Buku dihapus.');
    }
}
