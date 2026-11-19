<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (! Schema::hasColumn('loans', 'anggota_id')) {
                $table->foreignId('anggota_id')->nullable()->after('book_id')->constrained('anggotas')->nullOnDelete();
            }
            if (! Schema::hasColumn('loans', 'booking_code')) {
                $table->string('booking_code', 20)->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('loans', 'is_extended')) {
                $table->boolean('is_extended')->default(false)->after('status');
            }
            // Buat member_id nullable jika kolom lama masih ada agar tidak error saat menyimpan
            if (Schema::hasColumn('loans', 'member_id')) {
                $table->unsignedBigInteger('member_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['anggota_id']);
            $table->dropColumn(['anggota_id', 'booking_code', 'is_extended']);
        });
    }
};