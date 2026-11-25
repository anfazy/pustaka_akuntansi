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
        if (Schema::hasColumn('books', 'rak')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('rak');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('books', 'rak')) {
            Schema::table('books', function (Blueprint $table) {
                $table->string('rak', 100)->nullable()->after('stok');
            });
        }
    }
};
