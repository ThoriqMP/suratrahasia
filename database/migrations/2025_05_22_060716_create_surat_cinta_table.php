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
        Schema::create('surat_cinta', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('dari');
            $table->string('untuk');
            $table->text('isi');
            $table->string('password');
            $table->timestamp('dibuka_pada')->nullable();  // Waktu pertama kali surat dibuka
            $table->integer('waktu_hapus')->nullable();    // Jumlah hari sebelum dihapus
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_cinta');
    }
};
