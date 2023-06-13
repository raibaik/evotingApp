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
        Schema::create('importpemilih', function (Blueprint $table) {
            $table->id('IdPemilih');
            $table->string('nama')->default('Pemilihan Ketua Osis');
            $table->unsignedbigInteger('IdAngkatan');
            $table->unsignedbigInteger('IdStatus');
            $table->text('deskripsi');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('IdAngkatan')->references('IdAngkatan')->on('angkatan')->onDelete('cascade');
            $table->foreign('Idstatus')->references('IdStatus')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('importpemilih');
    }
};
