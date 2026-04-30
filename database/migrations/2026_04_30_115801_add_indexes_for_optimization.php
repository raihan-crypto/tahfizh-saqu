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
        Schema::table('setorans', function (Blueprint $table) {
            $table->index('tanggal');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('kelas_tingkat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setorans', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['kelas_tingkat']);
        });
    }
};
