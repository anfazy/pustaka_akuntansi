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
        Schema::create('book_covers', function (Blueprint $table) {
            $table->id();
            $table->string('path')->unique();
            $table->string('mime_type', 80);
            $table->longText('data');
            $table->timestamps();
        });

        if (Schema::hasColumn('books', 'cover_data')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('cover_data');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('books', 'cover_data')) {
            Schema::table('books', function (Blueprint $table) {
                $table->longText('cover_data')->nullable()->after('cover');
            });
        }

        Schema::dropIfExists('book_covers');
    }
};
