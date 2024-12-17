<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    protected $table ="folders";
    protected $primaryKey = "id";
    public $timestamps = true; // Jika tabel memiliki kolom created_at dan updated_at
    protected $fillable = [
        'id_author',
        'id_kategori',
        'folder_document',
        'keterangan',
        'author'
    ];

    // Menambahkan relasi ke model Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
