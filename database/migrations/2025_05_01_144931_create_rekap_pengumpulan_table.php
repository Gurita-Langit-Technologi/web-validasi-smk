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
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_mapel');
            $table->string('nama_tugas');
            $table->date('tanggal_pengumpulan')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('nilai')->default(0);
            $table->string('status')->default('Belum Selesai');
            $table->timestamps();

            $table->foreign('id_rekap_kelas')->references('id_rekap_kelas')->on('rekap_kelas')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_mapel')->references('id_mapel')->on('mapel')->onDelete('cascade');
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
