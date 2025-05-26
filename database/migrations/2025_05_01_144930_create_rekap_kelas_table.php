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
        Schema::create('rekap_kelas', function (Blueprint $table) {
            $table->id('id_rekap_kelas');
            $table->bigInteger('id_kelas')->reference('id_kelas')->on('kelas');
            $table->bigInteger('id_mapel')->reference('id_mapel')->on('mapel');
            $table->bigInteger('id_guru')->reference('id_guru')->on('guru');
            $table->bigInteger('id_wali_kelas')->reference('id_wali_kelas')->on('wali_kelas');
            $table->integer('total_tugas')->length(30)->default(0);
            $table->integer('jumlah_selesai')->length(30)->default(0);
            $table->integer('jumlah_tanggungan')->length(30)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_kelas');
    }
};
