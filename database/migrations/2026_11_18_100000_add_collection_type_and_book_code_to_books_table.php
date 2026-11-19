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
        Schema::table('books', function (Blueprint $table) {
            $table->string('collection_type', 30)->default('umum')->after('category_id');
            $table->string('book_code', 50)->nullable()->unique()->after('collection_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique(['book_code']);
            $table->dropColumn(['collection_type', 'book_code']);
        });
    }
};
