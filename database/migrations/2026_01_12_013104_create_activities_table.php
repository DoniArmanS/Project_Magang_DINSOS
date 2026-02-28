<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama');
            $table->datetime('tanggal');
            $table->text('kegiatan');
            $table->string('status')->default('Sedang Proses'); // Selesai | Sedang Proses
            $table->string('kategori'); // ODGJ, Terlantar, dll
            $table->string('jenis_kelamin'); // Laki-laki | Perempuan
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_tinggal')->nullable();
            $table->string('foto_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};