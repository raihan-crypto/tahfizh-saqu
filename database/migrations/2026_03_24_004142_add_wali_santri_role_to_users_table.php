<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change role column from enum to string to support new roles
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 50)->default('admin')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Note: Reverting to enum might not be natively supported perfectly by all drivers via structural change, 
            // but string will do in reverse if enum is no longer strictly enforced, or we leave as string.
            $table->string('role')->default('admin')->change();
        });
    }
};
