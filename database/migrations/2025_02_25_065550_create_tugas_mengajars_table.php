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
            $table->unsignedBigInteger('id_guru');
            $table->unsignedBigInteger('id_mapel');
            $table->unsignedBigInteger('id_kelas');

            $table->foreign('id_kelas')->references('id_kelas')->on('kelas');
            $table->foreign('id_guru')->references('id_guru')->on('guru');
            $table->foreign('id_mapel')->references('id_mapel')->on('mapel');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_mengajar'); // perbaiki jadi singular
    }
};
