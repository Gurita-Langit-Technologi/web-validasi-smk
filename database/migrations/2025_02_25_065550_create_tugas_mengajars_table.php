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
        /*
        Schema::create('tugas_mengajar', function (Blueprint $table) {
            $table->id('id_mengajar');
            $table->unsignedBigInteger('id_guru');
            $table->unsignedBigInteger('id_mapel');
            $table->unsignedBigInteger('id_kelas');
            $table->string('kode_guru')->nullable();
            $table->string('nama_guru');
            $table->string('kelas');
            $table->string('mata_diklat');
            $table->string('kompetensi_keahlian');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas');
            $table->foreign('id_guru')->references('id_guru')->on('guru');
            $table->foreign('id_mapel')->references('id_mapel')->on('mapel');
            $table->timestamps();
        });
        */

        /**
         * Run the migrations.
         */


        Schema::create('tugas_mengajar', function (Blueprint $table) {
            $table->id('id_mengajar');

            // Relasi ke tabel guru
            $table->unsignedBigInteger('id_guru');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');

            // Relasi ke tabel kelas
            $table->unsignedBigInteger('id_kelas');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');

            // Relasi ke tabel mapel
            $table->unsignedBigInteger('id_mapel');
            $table->foreign('id_mapel')->references('id_mapel')->on('mapel')->onDelete('cascade');

            // Tambahan jika butuh metadata lain
            $table->string('semester')->nullable();
            $table->string('tahun_ajaran')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_mengajars');
    }
};
