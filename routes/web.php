<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuratController;

Route::get('/', [SuratController::class, 'create']);
Route::post('/surat', [SuratController::class, 'store']);
Route::get('/surat/{kode}', [SuratController::class, 'show']);
Route::post('/surat/{kode}', [SuratController::class, 'unlock']);
