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
        Schema::table('loans', function (Blueprint $table) {
            $table->string('member_nim', 30)->nullable()->after('member_id');
            $table->string('card_type', 30)->default('KTM')->after('member_nim');
            $table->unsignedInteger('fine_amount')->default(0)->after('status');
            $table->boolean('is_lost')->default(false)->after('fine_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['member_nim', 'card_type', 'fine_amount', 'is_lost']);
        });
    }
};
