<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            if (! Schema::hasColumn('anggotas', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
            if (! Schema::hasColumn('anggotas', 'status')) {
                $table->string('status', 20)->default('active')->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn(['password', 'status']);
        });
    }
};
