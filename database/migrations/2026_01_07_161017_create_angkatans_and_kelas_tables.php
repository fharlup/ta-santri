<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('angkatans', function (Blueprint $table) {
        $table->id();
        $table->string('nama_angkatan')->unique(); // Contoh: "Angkatan 2024"
        $table->timestamps();
    });

    // Tabel Kelas (Contoh: 7A, 8B, 10C)
    Schema::create('kelas', function (Blueprint $table) {
        $table->id();
        $table->string('nama_kelas')->unique(); // Contoh: "Kelas 7A"
        $table->timestamps();
    }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angkatans_and_kelas_tables');
    }
};
