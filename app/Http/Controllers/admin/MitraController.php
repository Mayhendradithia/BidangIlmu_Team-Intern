<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\benefit;
use App\Models\Konfigurasi;

class MitraController extends Controller
{

    public function index()
    {
        $konfigurasi = Konfigurasi::all();
        $mitras = mitra::all();
        $benefits = benefit::all();
        return view('admin.landingAdmin', compact('konfigurasi', 'mitras', 'benefits'));
    }



    public function create()
    {
        return view('admin.mitra.create');
    }


    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // Buat instance baru dari model Mitra
        $mitra = new Mitra();

        // Cek apakah ada file yang diunggah
        if ($request->hasFile('image')) {
            // Simpan gambar ke dalam folder public/mitra_images
            // (store('mitra_images') akan menyimpannya di storage/app/public/mitra_images)
            $mitra->image = $request->file('image')->store('mitra_images', 'public');
        }

        // Simpan data mitra ke database
        $mitra->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('konfigurasi.index')->with('success', 'Konfigurasi berhasil diupdate.');
    }

    public function edit($id)
    {
        $mitra = Mitra::findOrFail($id); // Mengambil data berdasarkan ID
        return view('admin.mitra.edit', compact('mitra')); // Kirim data ke view
    }

    // Fungsi untuk mengupdate gambar
    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|max:2048', // Validasi untuk gambar
        ]);

        $mitra = Mitra::findOrFail($id);

        if ($request->hasFile('image')) {
            // Menghapus gambar lama jika ada
            if ($mitra->image && Storage::exists($mitra->image)) {
                Storage::delete($mitra->image);
            }

            // Simpan gambar baru
            $filePath = $request->file('image')->store('mitra_images');
            $mitra->image = $filePath;
        }

        $mitra->save();

        return redirect()->route('konfigurasi.index')->with('success', 'Mitra berhasil diperbarui!');
    }

    // Fungsi untuk menghapus gambar
    public function destroy(Mitra $mitra)
    {
        if ($mitra->image) {
            Storage::delete($mitra->image);
        }

        $mitra->delete();

        return redirect()->back()->with('success', 'Gambar berhasil dihapus');
    }
}
