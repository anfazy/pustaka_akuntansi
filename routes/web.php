<?php

use App\Http\Controllers\AnggotaTemplateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/anggota/download-template', [AnggotaTemplateController::class, 'download'])
    ->name('admin.anggota.download-template');

