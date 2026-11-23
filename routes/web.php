<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AnggotaTemplateController;
use App\Http\Controllers\UserPortalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Pustaka Akuntansi
|--------------------------------------------------------------------------
| Frontend User / Mahasiswa: /
| Backend Admin / Pustakawan: /admin (Filament Panel)
*/

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
Route::match(['get', 'post'], '/keluar', [UserPortalController::class, 'keluar'])->name('keluar');

// Admin Custom Multi-Page Routes (Persis Mockup dengan URL Terpisah)
Route::middleware(['web'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('filament.admin.pages.dashboard');
    Route::get('/admin/buku', [AdminDashboardController::class, 'buku'])->name('filament.admin.resources.buku.index');
    Route::get('/admin/anggota', [AdminDashboardController::class, 'anggota'])->name('filament.admin.resources.anggota.index');
    Route::get('/admin/peminjaman', [AdminDashboardController::class, 'peminjaman'])->name('filament.admin.resources.peminjaman.index');
    Route::get('/admin/kategori', [AdminDashboardController::class, 'kategori'])->name('filament.admin.resources.kategori.index');

    // Admin API Routes (Database persistence untuk form & aksi di mockup)
    Route::post('/admin/api/anggota', [AdminDashboardController::class, 'saveAnggota'])->name('admin.api.anggota.save');
    Route::delete('/admin/api/anggota/{id}', [AdminDashboardController::class, 'deleteAnggota'])->name('admin.api.anggota.delete');
    Route::post('/admin/api/books', [AdminDashboardController::class, 'saveBook'])->name('admin.api.books.save');
    Route::delete('/admin/api/books/{id}', [AdminDashboardController::class, 'deleteBook'])->name('admin.api.books.delete');
    Route::post('/admin/api/loans', [AdminDashboardController::class, 'saveLoan'])->name('admin.api.loans.save');
    Route::post('/admin/api/loans/{id}/return', [AdminDashboardController::class, 'returnLoan'])->name('admin.api.loans.return');
    Route::post('/admin/api/loans/{id}/confirm-handover', [AdminDashboardController::class, 'confirmHandoverLoan'])->name('admin.api.loans.handover');
    Route::post('/admin/api/loans/{id}/cancel-booking', [AdminDashboardController::class, 'cancelBookingAdmin'])->name('admin.api.loans.cancel-booking');
    Route::post('/admin/api/loans/lookup-booking', [AdminDashboardController::class, 'lookupBooking'])->name('admin.api.loans.lookup-booking');
});

// Admin Helper Routes
Route::get('/admin/anggota/download-template', [AnggotaTemplateController::class, 'download'])
    ->name('admin.anggota.download-template');
