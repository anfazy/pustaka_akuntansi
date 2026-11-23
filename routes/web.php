<?php

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

// Admin Helper Routes
Route::get('/admin/anggota/download-template', [AnggotaTemplateController::class, 'download'])
    ->name('admin.anggota.download-template');
