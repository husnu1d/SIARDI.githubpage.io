<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search'); // Ambil nilai pencarian
    $kategori = Kategori::query()
        ->when($search, function ($query, $search) {
            return $query->where('nama_kategori', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10); // Tetap menggunakan pagination

    return view('dashboard', compact('kategori', 'search'));
}


    public function store(Request $request)
    {
    $request->validate([
        'nama_kategori' => 'required|string|max:255',
    ]);

    Kategori::create([
        'nama_kategori' => $request->nama_kategori,
    ]);
    return redirect()->back()->with('success', 'Kategori created successfully.');
    }

    public function update(Request $request, $id){
    $request->validate([
        'nama_kategori' => 'required|string|max:255',
    ]);

    $Kategori = Kategori::findOrFail($id);
    $Kategori->update([
        'nama_kategori' => $request->nama_kategori,
    ]);
       $nama = $request->input('nama_kategori');
        return redirect()->route('dashboard')->with('success', 'Data kategori ' . $nama . ' berhasil diubah');


    }

public function delete($id)
{
    // Temukan kategori berdasarkan ID
    $kategori = Kategori::findOrFail($id);

    // Hapus semua folder yang terkait dengan kategori ini
    $kategori->folders()->delete();

    // Hapus kategori itu sendiri
    if ($kategori->delete()) {
        return redirect()->route('dashboard')->with('success', 'Data berhasil dihapus beserta folder terkait.');
    } else {
        return redirect()->route('dashboard')->with('danger', 'Data tidak dapat dihapus');
    }
}

}