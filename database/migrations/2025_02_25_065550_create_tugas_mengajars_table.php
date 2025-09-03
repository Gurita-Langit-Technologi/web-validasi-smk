<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_mengajar', function (Blueprint $table) {
            $table->id('id_mengajar');

            // Relasi ke tabel guru -> pakai kode_guru
            $table->string('kode_guru', 20)->collation('utf8mb4_unicode_ci');
            $table->foreign('kode_guru')
                ->references('kode_guru')
                ->on('guru')
                ->onDelete('cascade');

            // Relasi ke tabel kelas -> pakai kode_kelas
            $table->string('kode_kelas', 20)->collation('utf8mb4_unicode_ci');
            $table->foreign('kode_kelas')
                ->references('kode_kelas')
                ->on('kelas')
                ->onDelete('cascade');

            // Relasi ke tabel mapel -> pakai kode_mapel
            $table->string('kode_mapel', 20)->collation('utf8mb4_unicode_ci');
            $table->foreign('kode_mapel')
                ->references('kode_mapel')
                ->on('mapel')
                ->onDelete('cascade');



            // Metadata
            $table->string('semester')->nullable();
            $table->string('tahun_ajaran')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_mengajar'); // perbaiki jadi singular
    }
};
