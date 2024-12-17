<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUnmergedFilePathColumnInDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Ubah kolom menjadi tipe TEXT agar bisa menampung data yang lebih besar
            $table->text('unmerged_file_path')->change();
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Kembalikan kolom ke tipe string dengan panjang 255 karakter jika perlu
            $table->string('unmerged_file_path', 255)->change();
        });
    }
}
