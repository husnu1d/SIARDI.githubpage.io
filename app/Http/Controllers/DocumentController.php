<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Document;
use App\Models\Folder;
use Illuminate\Support\Facades\File;

class DocumentController extends Controller
{
    /**
     * Display the upload form.
     *
     * @return \Illuminate\View\View
     */
public function index()
{
    // Mengambil data dokumen dengan join tabel folders
        $folders = Folder::all();

    $documents = Document::with('folder') // Menggunakan eager loading untuk join
        ->orderBy('id', 'desc')
        ->paginate(10); // Urutkan dan paginate
    
    return view('documents.index', compact('documents','folders'));
}

public function ajax(Request $request)
{
    // Mengecek apakah ada parameter 'page' (untuk paginasi)
    $folders = Folder::all();
    $page = $request->input('page', 1);
    // Ambil data dokumen sesuai dengan halaman yang diminta, dengan join folder
    $documents = Document::with('folder')
        ->orderBy('id', 'desc')
        ->paginate(10, ['*'], 'page', $page);

    // Mengembalikan data dokumen dalam format JSON
    return response()->json([
        'documents' => $documents->items(),
        'folders' => $folders,
        'next_page' => $documents->hasMorePages() ? $documents->currentPage() + 1 : null
    ]);
}



    // public function dashboard()
    // {
    //     // Define the path to the Split folder
    //     $splitPath = storage_path('app/public/Split'); 

    //     // Initialize an array to hold the folder data
    //     $foldersData = [];
    //     $uploadDate = ''; // Initialize the variable for upload date

    //     // Check if the Split folder exists
    //     if (File::exists($splitPath)) {
    //         // Get all subdirectories inside the Split directory (which are the upload date folders)
    //         $dateFolders = File::directories($splitPath);

    //         // Iterate through the date folders
    //         foreach ($dateFolders as $dateFolder) {
    //             // Get the name of the date folder (which is the upload date)
    //             $uploadDate = basename($dateFolder); // Set the upload date dynamically

    //             // Get all subdirectories inside the date folder (which are the actual folders inside the upload date folder)
    //             $subFolders = File::directories($dateFolder);

    //             // Iterate through the subfolders and gather the folder data
    //             foreach ($subFolders as $subFolder) {
    //                 $subFolderName = basename($subFolder);
                    
    //                 // Add the folder name to the foldersData array
    //                 $foldersData[] = [
    //                     'folder_name' => $subFolderName, // Folder name (without date)
    //                     'folder_path' => 'storage/Split/' . basename($uploadDate) . '/' . $subFolderName,
    //                     'file_count' => count(File::files($subFolder)), // Number of files in the folder
    //                     'upload_date' => $uploadDate, // Pass the upload date for the link
    //                 ];
    //             }
    //         }
    //     }

    //     // Return the view with the folders data
    //     return view('dashboard', compact('foldersData'));
    // }


    public function show($uploadDate, $folderName)
    {
        // Fetch all documents from the Document model
        $documents = Document::all(); // Retrieves all records from the Document model

        // Check if we're viewing the root (date) folder or a subfolder
        if ($folderName == 'root') {
            $folderPath = storage_path('app/public/Split/' . $uploadDate);
            $folders = File::directories($folderPath); // Get all subfolders inside the date folder

            return view('documents.show', compact('folders', 'uploadDate', 'folderName', 'documents'));
        } else {
            // If it's a subfolder inside the date folder
            $folderPath = storage_path('app/public/Split/' . $uploadDate . '/' . $folderName);

            if (File::isDirectory($folderPath)) {
                $subFolders = File::directories($folderPath); // Get subfolders inside the subfolder
                $files = File::files($folderPath); // Get files inside the subfolder
            } else {
                // Handle case where folder is not found
                return back()->withErrors(['error' => 'Folder not found.']);
            }

            return view('documents.show', compact('subFolders', 'files', 'uploadDate', 'folderName', 'documents'));
        }
    }

     public function split()
    {
        return view('documents.file');
    }

    public function upload(Request $request)
    {
        try {
            $files = $request->file('document'); // Mengambil semua file yang diunggah

            // Pastikan ada file yang diunggah
            if (empty($files)) {
                return back()->withErrors(['error' => 'Tidak ada file yang diunggah.']);
            }

            // Loop untuk memproses setiap file yang diunggah
            foreach ($files as $file) {
                // Ambil informasi file
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileSize = $file->getSize();
                $fileType = $file->getMimeType();

                Log::info('File diunggah: ' . $originalFileName . '.' . $extension);

                // Format tanggal upload
                $uploadDate = now()->format('d-m-Y');

                // Path penyimpanan file asli
                $storagePath = 'documents/' . $originalFileName . '.' . $extension;
                $absoluteFilePath = storage_path('app/public/' . $storagePath);

                // Simpan file di folder documents
                $file->move(storage_path('app/public/documents'), $originalFileName . '.' . $extension);

                Log::info('File asli disimpan di: ' . $absoluteFilePath);

                // Folder hasil split (misalnya jika PDF)
                $splitFolderPath = storage_path('app/public/Split/' . $uploadDate . '/' . $originalFileName);
                if (!file_exists($splitFolderPath)) {
                    mkdir($splitFolderPath, 0777, true);
                }

                // Jika file adalah PDF, lakukan pemisahan (misalnya split PDF)
                $splitFilePaths = null;
                if ($extension === 'pdf') {
                    // Asumsi Anda memiliki fungsi splitPdfUsingFPDI untuk memisahkan PDF
                    $splitFilePaths = $this->splitPdfUsingFPDI($absoluteFilePath, $splitFolderPath);
                }

                // Simpan metadata ke database
                Document::create([
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $storagePath,
                    'unmerged_file_path' => $splitFilePaths ? implode(',', $splitFilePaths) : null,
                    'file_size' => $fileSize,
                    'file_type' => $fileType,
                    'upload_date' => now(),
                    'is_unmerged' => $splitFilePaths ? true : false,
                ]);
            }

            return back()->with('success', 'File berhasil di-upload dan dibagi per halaman!');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat mengunggah file: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
public function delete($id)
{
    try {
        // Temukan dokumen berdasarkan ID
        $document = Document::findOrFail($id);

        // Path file asli
        $filePath = storage_path('app/public/' . $document->file_path);
        // Path untuk file yang dibagi (jika ada)
        $unmergedFilePaths = explode(',', $document->unmerged_file_path);

        // Hapus file asli
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Hapus file hasil split (jika ada)
        foreach ($unmergedFilePaths as $splitFilePath) {
            $splitFilePath = storage_path('app/public/' . $splitFilePath);
            if (File::exists($splitFilePath)) {
                File::delete($splitFilePath);
            }
        }

        // Hapus data dokumen dari database
        $document->delete();

        return back()->with('success', 'Dokumen dan file terkait berhasil dihapus.');
    } catch (\Exception $e) {
        Log::error('Kesalahan saat menghapus dokumen: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}



    protected function splitPdfUsingFPDI($filePath, $splitFolderPath)
    {
        try {
            // Pastikan file ada
            if (!file_exists($filePath)) {
                throw new \Exception('File PDF tidak ditemukan: ' . $filePath);
            }

            // Inisialisasi FPDI
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($filePath);
            if ($pageCount <= 0) {
                throw new \Exception('Tidak ada halaman ditemukan di file PDF: ' . $filePath);
            }

            // Ambil nama asli file
            $originalFileName = pathinfo($filePath, PATHINFO_FILENAME);

            // Pastikan folder untuk hasil split sudah ada
            if (!is_dir($splitFolderPath)) {
                if (!mkdir($splitFolderPath, 0777, true)) {
                    throw new \Exception('Gagal membuat folder untuk file hasil split: ' . $splitFolderPath);
                }
            }

            // Array untuk menyimpan path file hasil split
            $splitFilePaths = [];

            // Proses setiap halaman dari file PDF
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $newPdf = new \setasign\Fpdi\Fpdi();
                $newPdf->setSourceFile($filePath);
                $templateId = $newPdf->importPage($pageNo);

                // Dapatkan ukuran halaman asli
                $size = $newPdf->getTemplateSize($templateId);
                $newPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $newPdf->useTemplate($templateId);

                // Tentukan nama dan path untuk file split
                $splitFileName = 'SM_' . $pageNo . '.pdf';
                $newFilePath = $splitFolderPath . '/' . $splitFileName;

                // Simpan file PDF hasil split
                $newPdf->Output($newFilePath, 'F');

                // Tambahkan path ke array hasil split
                $splitFilePaths[] = 'Split/' . basename($splitFolderPath) . '/' . $splitFileName;
            }

            return $splitFilePaths;

        } catch (\Exception $e) {
            \Log::error('Kesalahan FPDI: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses file PDF: ' . $e->getMessage());
        }
    }
}