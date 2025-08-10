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
        Schema::create('perwalian_kelas', function (Blueprint $table) {
            $table->id(); // auto increment primary key

            $table->unsignedBigInteger('id_guru');  // relasi ke guru
            $table->unsignedBigInteger('id_wali_kelas');
            $table->unsignedBigInteger('id_kelas'); // relasi ke kelas

            // Constraint unik supaya 1 guru hanya jadi 1 wali kelas
            $table->unique('id_guru');

            // Foreign keys
            $table->foreign('id_guru')
                ->references('id_guru')
                ->on('guru')
                ->onDelete('cascade');

            $table->foreign('id_wali_kelas')
                ->references('id_wali_kelas')
                ->on('wali_kelas');

            $table->foreign('id_kelas')
                ->references('id_kelas')
                ->on('kelas')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perwalian_kelas');
    }
};
