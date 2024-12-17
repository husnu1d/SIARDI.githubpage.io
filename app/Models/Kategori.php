<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Kategori extends Model
{
    use HasFactory;
    protected $table ="kategoris";
    protected $primaryKey = "id";
    public $timestamps = true; // Jika tabel memiliki kolom created_at dan updated_at

    protected $fillable = [
        'nama_kategori'
    ];

    public function folders()
    {
        return $this->hasMany(Folder::class, 'id_kategori');
    }
}
