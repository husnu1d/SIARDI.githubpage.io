<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $table = 'documents'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key tabel
    public $timestamps = true; // Jika tabel memiliki kolom created_at dan updated_at
    protected $fillable = [
        'name_name',
        'directory',
        'file_path',
        'unmerged_file_path',
        'file_size',
        'file_type',
        'is_unmerged',
    ];

    protected $casts = [
        'upload_date' => 'date',
        'is_unmerged' => 'boolean',
    ];


    // Relasi ke model Folder
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'id_folder', 'id');
    }

}
