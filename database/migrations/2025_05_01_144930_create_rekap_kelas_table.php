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
            $table->unsignedbigInteger('id_kelas');
            $table->unsignedbigInteger('id_mapel');
            $table->unsignedbigInteger('id_guru');
            $table->unsignedbigInteger('id_wali_kelas');
            $table->integer('total_tugas')->length(30)->default(0);
            $table->integer('jumlah_selesai')->length(30)->default(0);
            $table->integer('jumlah_tanggungan')->length(30)->default(0);
            $table->timestamps();

            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');
            $table->foreign('id_mapel')->references('id_mapel')->on('mapel')->onDelete('cascade');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            $table->foreign('id_wali_kelas')->references('id_wali_kelas')->on('wali_kelas')->onDelete('cascade');
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
