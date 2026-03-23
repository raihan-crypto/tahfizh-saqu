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
       Schema::create('setorans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
        $table->date('tanggal')->default(now());
        $table->enum('kehadiran', ['Hadir', 'Izin', 'Terlambat', 'Alpha', 'Sakit']);
        // ZIYADAH
        $table->integer('ziyadah_juz')->nullable();
        $table->string('ziyadah_surat')->nullable();
        $table->integer('ziyadah_ayat_mulai')->nullable();
        $table->integer('ziyadah_ayat_selesai')->nullable();
        $table->integer('ziyadah_baris')->nullable();

        // RABTH
        $table->integer('rabth_juz')->nullable();
        $table->string('rabth_surat')->nullable();
        $table->integer('rabth_ayat_mulai')->nullable();
        $table->integer('rabth_ayat_selesai')->nullable();
        $table->integer('rabth_baris')->nullable();

        // MURAJA'AH
        $table->integer('murajaah_juz')->nullable();
        $table->string('murajaah_surat')->nullable();
        $table->integer('murajaah_ayat_mulai')->nullable();
        $table->integer('murajaah_ayat_selesai')->nullable();
        $table->integer('murajaah_baris')->nullable();

        // PENILAIAN
        $table->integer('nilai_kelancaran')->default(100);
        $table->text('catatan')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setorans');
    }
};
