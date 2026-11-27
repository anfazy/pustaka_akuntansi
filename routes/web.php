<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AnggotaTemplateController;
use App\Http\Controllers\UserPortalController;
use App\Models\Book;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes - Perpustakaan Jurusan Akuntansi
|--------------------------------------------------------------------------
| Frontend User / Mahasiswa: /
| Backend Admin / Pustakawan: /admin (Filament Panel)
*/

// Penyaji File Storage Publik (memulihkan dari DB jika filesystem container Railway ter-reset)
Route::get('/storage-file/{path}', function (string $path) {
    $cleanPath = ltrim(str_replace('..', '', $path), '/');

    if (Storage::disk('public')->exists($cleanPath)) {
        return Storage::disk('public')->response($cleanPath);
    }

    $restored = Book::restoreCoverFromDatabase($cleanPath);
    if ($restored !== null) {
        return response($restored['binary'], 200, [
            'Content-Type' => $restored['mime'],
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }

    abort(404);
})->where('path', '.*')->name('storage.file');

// Public / Guest & Student Browsing
Route::get('/', [UserPortalController::class, 'beranda'])->name('beranda');
Route::get('/katalog', [UserPortalController::class, 'katalog'])->name('katalog');
Route::get('/tata-tertib', [UserPortalController::class, 'tataTertib'])->name('tata-tertib');
Route::get('/buku/{id}', [UserPortalController::class, 'bukuDetail'])->name('buku.detail');

// Booking & Favorite Action
Route::post('/buku/{id}/booking', [UserPortalController::class, 'booking'])->name('buku.booking');
Route::post('/buku/{id}/favorit', [UserPortalController::class, 'toggleFavorite'])->name('buku.favorit');

// Authenticated Student Features
Route::get('/favorit', [UserPortalController::class, 'favorit'])->name('favorit');
Route::get('/pinjaman', [UserPortalController::class, 'pinjaman'])->name('pinjaman');
Route::post('/pinjaman/{id}/batal', [UserPortalController::class, 'cancelBooking'])->name('pinjaman.batal');
Route::post('/pinjaman/{id}/perpanjang', [UserPortalController::class, 'extendLoan'])->name('pinjaman.perpanjang');
Route::get('/riwayat', [UserPortalController::class, 'riwayat'])->name('riwayat');

// Student Authentication
Route::get('/masuk', [UserPortalController::class, 'masuk'])->name('masuk');
Route::post('/masuk', [UserPortalController::class, 'prosesMasuk'])->name('masuk.proses');
Route::get('/daftar', [UserPortalController::class, 'daftar'])->name('daftar');
Route::post('/daftar', [UserPortalController::class, 'prosesDaftar'])->name('daftar.proses');
Route::get('/verifikasi-email', [UserPortalController::class, 'verifikasiNotice'])->name('verifikasi.notice');
Route::get('/verifikasi-email/{id}/{hash}', [UserPortalController::class, 'verifikasiEmail'])
    ->middleware('signed')
    ->name('verifikasi.email');
Route::post('/verifikasi-email/kirim-ulang', [UserPortalController::class, 'kirimUlangVerifikasi'])
    ->middleware('throttle:3,1')
    ->name('verifikasi.resend');
Route::match(['get', 'post'], '/keluar', [UserPortalController::class, 'keluar'])->name('keluar');

// Admin Mockup (Arsip Prototype) & Redirect Dashboard
Route::get('/admin/mockup', [AdminDashboardController::class, 'index'])->name('admin.mockup');
Route::get('/admin/dashboard', fn () => redirect('/admin'))->name('admin.dashboard');
Route::post('/admin/api/anggota', [AdminDashboardController::class, 'saveAnggota'])->name('admin.api.anggota.save');
Route::delete('/admin/api/anggota/{id}', [AdminDashboardController::class, 'deleteAnggota'])->name('admin.api.anggota.delete');
Route::post('/admin/api/books', [AdminDashboardController::class, 'saveBook'])->name('admin.api.books.save');
Route::delete('/admin/api/books/{id}', [AdminDashboardController::class, 'deleteBook'])->name('admin.api.books.delete');
Route::post('/admin/api/loans', [AdminDashboardController::class, 'saveLoan'])->name('admin.api.loans.save');
Route::post('/admin/api/loans/{id}/return', [AdminDashboardController::class, 'returnLoan'])->name('admin.api.loans.return');
Route::post('/admin/api/loans/{id}/confirm-handover', [AdminDashboardController::class, 'confirmHandoverLoan'])->name('admin.api.loans.handover');
Route::post('/admin/api/loans/{id}/cancel-booking', [AdminDashboardController::class, 'cancelBookingAdmin'])->name('admin.api.loans.cancel-booking');
Route::post('/admin/api/loans/lookup-booking', [AdminDashboardController::class, 'lookupBooking'])->name('admin.api.loans.lookup-booking');

// Admin Helper Routes
Route::get('/admin/anggota/download-template', [AnggotaTemplateController::class, 'download'])
    ->name('admin.anggota.download-template');
