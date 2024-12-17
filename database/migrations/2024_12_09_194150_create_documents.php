<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('original_name'); // Nama asli file
            $table->string('unmerged_file_path')->nullable(); // Lokasi file hasil unmerge
            $table->date('upload_date');     // Tanggal upload file
            $table->boolean('is_unmerged')->default(false); // Status apakah sudah di-unmerge atau belum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
