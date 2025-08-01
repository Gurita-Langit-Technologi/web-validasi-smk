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
        Schema::create('mapel', function (Blueprint $table) {
            $table->id('id_mapel');
            $table->string('kode_mapel')->unique();
            $table->string('nama_diklat', 80);
            $table->unsignedBigInteger('id_guru')->nullable(); // dibuat nullable
            $table->timestamps();

            $table->foreign('id_guru')
                ->references('id_guru')
                ->on('guru')
                ->onUpdate('cascade')
                ->onDelete('restrict'); // jika masih ingin menolak penghapusan guru yang sedang dipakai
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapel'); // perbaiki dari 'mapels'
    }
};
