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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan'); // Pastikan hanya ada satu baris ini
        $table->time('jam'); 
        $table->date('tanggal'); // Sesuai alur pembuatan presensi 
        $table->string('angkatan'); // Untuk rekap per angkatan [cite: 11]
        $table->string('ustadzah_1')->nullable(); // Ustadzah yang membersamai 
        $table->string('ustadzah_2')->nullable();
        $table->string('ustadzah_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
