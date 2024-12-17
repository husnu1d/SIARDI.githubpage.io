<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('documents', function (Blueprint $table) {
        $table->string('unmerged_file_path', 1000)->change();  // Menyesuaikan panjang kolom
    });
}
    /**
     * Reverse the migrations.
     */
  public function down()
{
    Schema::table('documents', function (Blueprint $table) {
        $table->string('unmerged_file_path', 255)->change();  // Mengembalikan ke panjang semula
    });
}
};
