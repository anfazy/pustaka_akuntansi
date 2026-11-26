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
        Schema::table('anggotas', function (Blueprint $table) {
            if (! Schema::hasColumn('anggotas', 'kota')) {
                $table->string('kota', 100)->nullable()->after('alamat');
            }
            if (! Schema::hasColumn('anggotas', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
        });

        DB::table('anggotas')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $columns = array_filter(
                ['kota', 'email_verified_at'],
                fn (string $column): bool => Schema::hasColumn('anggotas', $column)
            );

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
