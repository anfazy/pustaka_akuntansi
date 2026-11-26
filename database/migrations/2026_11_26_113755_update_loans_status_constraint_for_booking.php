<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE loans DROP CONSTRAINT IF EXISTS loans_status_check');
            DB::statement("ALTER TABLE loans ALTER COLUMN status TYPE VARCHAR(30), ALTER COLUMN status SET DEFAULT 'dipinjam'");
            DB::statement("ALTER TABLE loans ADD CONSTRAINT loans_status_check CHECK (status IN ('booked', 'dipinjam', 'dikembalikan', 'terlambat', 'dibatalkan'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE loans DROP CONSTRAINT IF EXISTS loans_status_check');
            DB::statement("ALTER TABLE loans ADD CONSTRAINT loans_status_check CHECK (status IN ('dipinjam', 'dikembalikan', 'terlambat'))");
        }
    }
};
