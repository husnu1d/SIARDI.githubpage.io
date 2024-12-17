<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Folder;
use App\Models\Kategori;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $categories = Kategori::all();
        $search = $request->input('search'); // Ambil nilai pencarian

        // Mengambil folder dengan kategori yang terkait menggunakan eager loading
        $folders = Folder::with('kategori')  // Eager Load kategori
            ->when($search, function ($query, $search) {
                return $query->where('folder_document', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10); // Tetap menggunakan pagination
        
        // Mengambil folder yang ada di storage/app/public
        $foldersInStorage = Storage::disk('public')->directories();

        return view('folders.index', compact('folders', 'foldersInStorage', 'search', 'categories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_kategori' => 'required',
            'id_author' => 'required',
            'folder_document' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255'
        ]);

        // Gunakan nama folder langsung tanpa subfolder `folder_documents`
        $folderPath = $request->folder_document;

        // Membuat folder di dalam storage/public
        if (!Storage::disk('public')->exists($folderPath)) {
            // Membuat folder jika belum ada
            Storage::disk('public')->makeDirectory($folderPath);
        }

        // Simpan informasi folder ke dalam database
        Folder::create([
            'id_kategori' => $request->id_kategori,
            'id_author' => $request->id_author,
            'folder_document' => $request->folder_document,
            'author' => $request->author,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'Folder Berkas Berhasil Dibuat.');
    }

    public function show($id)
    {
        // Mengambil data folder berdasarkan id
        $folder = Folder::findOrFail($id);

        // Path folder yang ada di storage tanpa subfolder `folder_documents`
        $folderPath = $folder->folder_document;

        // Mengecek apakah folder ada di storage
        if (Storage::disk('public')->exists($folderPath)) {
            $files = Storage::disk('public')->files($folderPath);
        } else {
            return redirect()->route('folders.index')->with('error', 'Folder tidak ditemukan.');
        }

        return view('folders.show', compact('folder', 'files'));
    }
}