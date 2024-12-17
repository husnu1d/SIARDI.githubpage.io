<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('auth.login');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/help', function () {
    return view('documents.help');
})->middleware(['auth', 'verified'])->name('help');

Route::get('/File', function () {
    return view('documents.file');
})->middleware(['auth', 'verified'])->name('File');

Route::get('/Split', function () {
    return view('documents.index');
})->middleware(['auth', 'verified'])->name('Split');

Route::get('/Convert', function () {
    return view('converts.index');
})->middleware(['auth', 'verified'])->name('Convert');

Route::get('/MyProfile', function () {
    return view('MyProfile');
})->middleware(['auth', 'verified'])->name('MyProfile');

Route::get('/Settings', function () {
    return view('Settingse');
})->middleware(['auth', 'verified'])->name('Settingse');


// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/documents/ajax', [DocumentController::class, 'ajax'])->name('documents.ajax');
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents');
    
    Route::get('/documents/file', [DocumentController::class, 'file'])->name('documents.file');
    Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/split', [DocumentController::class, 'split'])->name('documents.split');
    // Update this line to use GET for dashboard
    Route::delete('/documents/{id}', [DocumentController::class, 'delete'])->name('documents.delete');
    Route::get('/documents/show/{uploadDate}/{folderName}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/file/{uploadDate}/{fileName}', [DocumentController::class, 'showFile'])->name('documents.showFile');
    
    // Route Kategori
    Route::get('/dashboard', [KategoriController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/update/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/delete/{id}', [KategoriController::class, 'delete'])->name('kategori.delete');

    // Route Folder
    Route::get('/folders', [FolderController::class, 'index'])->name('folders');
    Route::post('/folders/store', [FolderController::class, 'store'])->name('folders.store');
    Route::get('/folders/show/{id}', [FolderController::class, 'show'])->name('folders.show');
    Route::get('/folders/update/{id}', [FolderController::class, 'update'])->name('folders.update');
});


require __DIR__.'/auth.php';
