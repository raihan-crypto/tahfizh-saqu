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
        Schema::create('santris', function (Blueprint $table) {
        $table->id();
        $table->string('nisn')->unique();
        $table->string('nama_santri');
        $table->string('jenis_kelamin');
        $table->foreignId('kelas_halaqah_id')->constrained('kelas_halaqahs')->cascadeOnDelete();
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('nama_orangtua');
        $table->string('wa_orangtua');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
