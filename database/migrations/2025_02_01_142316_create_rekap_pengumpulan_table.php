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
        Schema::create('rekap_pengumpulan', function (Blueprint $table) {
            $table->id('id_tugas');
            $table->unsignedBigInteger('id_rekap_kelas');
            $table->bigInteger('id_siswa')->reference('id_siswa')->on('siswa');
            $table->bigInteger('id_mapel')->reference('id_mapel')->on('mapel');
            $table->string('nama_tugas');
            $table->date('tanggal_pengumpulan')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('status')->default('Belum Selesai');
            $table->timestamps();

            $table->foreign('id_rekap_kelas')->references('id_rekap_kelas')->on('rekap_kelas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
