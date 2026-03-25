<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {

            // Kolom baru untuk optimasi observer
            $table->integer('total_hafalan_baris')->default(0);
            
            // Soft delete
            $table->softDeletes();
        });

        Schema::table('ustadzs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('setorans', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {

            $table->dropColumn('total_hafalan_baris');
            $table->dropSoftDeletes();
        });

        Schema::table('ustadzs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('setorans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
