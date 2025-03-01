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
        Schema::create('user_guru', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guru_id');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->timestamps();

            $table->foreign('guru_id')->references('id_guru')->on('guru')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_gurus');
    }
};
