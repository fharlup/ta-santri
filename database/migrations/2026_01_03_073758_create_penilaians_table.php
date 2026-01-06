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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santriwati_id')->constrained();
    $table->date('tanggal'); // Tanggal penilaian/pelanggaran [cite: 31]
    $table->string('angkatan'); // Angkatan saat dinilai [cite: 31]
    
    // Muatan Karakter (Predikat A/B/C) 
    $table->char('disiplin', 1)->default('B'); 
    $table->char('k3', 1)->default('B'); 
    $table->char('tanggung_jawab', 1)->default('B');
    $table->char('inisiatif_kreatifitas', 1)->default('B');
    $table->char('adab', 1)->default('B');
    $table->char('berterate', 1)->default('B');
    
    // Integritas Santri 
    $table->char('integritas_kesabaran', 1)->default('B');
    $table->char('integritas_produktif', 1)->default('B');
    $table->char('integritas_mandiri', 1)->default('B');
    $table->char('integritas_optimis', 1)->default('B');
    $table->char('integritas_kejujuran', 1)->default('B');
    
    $table->text('deskripsi')->nullable(); // Catatan tambahan
      $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
