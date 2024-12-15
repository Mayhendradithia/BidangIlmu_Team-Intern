<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('admin.kategoris.index', compact('kategoris'));
    }

    public function navbar()
    {
        // Data untuk navbar
        $kategoris = Kategori::all();
        return view('layout', compact('kategoris'));
    }
    public function create()
    {
        return view('admin.kategoris.create');
    }

    public function store(Request $request)
    {
        try {
            // Validasi dan simpan kategori
            $request->validate([
                'nama' => 'required|string|max:255',
            ]);

            // Simpan data kategori
            Kategori::create([
                'nama' => $request->nama,
            ]);

            // Kirimkan pesan sukses dan redirect ke halaman index
            return redirect()->route('kategoris.create')->with('success', 'Kategori berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Kirimkan pesan error jika ada kesalahan
            return redirect()->route('kategoris.create')->with('error', 'Terjadi kesalahan, kategori gagal ditambahkan.');
        }
    }



    public function edit(Kategori $kategori)
    {
        return view('admin.kategoris.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->nama = $request->nama;
        $kategori->save();

        return redirect()->route('kategoris.index')->with('success', 'Kategori Berhasil Diperbarui');
    }


    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategoris.destroy')->with('success', 'Kategori berhasil dihapus.');
    }
}
