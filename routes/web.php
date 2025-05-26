<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuratController;
use App\Http\Controllers\AnonRoomController;
use App\Http\Controllers\AnonMessageController;

Route::get('/', [SuratController::class, 'index']);
Route::get('/index', [SuratController::class, 'index']);
Route::get('/create', [SuratController::class, 'create']);
Route::post('/surat', [SuratController::class, 'store']);
Route::get('/surat/{kode}', [SuratController::class, 'show']);
Route::post('/surat/{kode}', [SuratController::class, 'unlock']);
Route::get('/kontak', function () {
    return view('kontak');
});

Route::get('/tentang', function () {
    return view('tentang');
});
Route::get('/anonim', function () {
    return view('pembuatan');
});

Route::fallback(function () {
    return response()->view('notfound', [], 404);
});

Route::get('/statistik', [SuratController::class, 'showStatistikForm'])->name('statistik.form');
Route::post('/statistik', [SuratController::class, 'statistik'])->name('statistik.show');


Route::post('/statistik/cari', [SuratController::class, 'hasilPencarian'])->name('statistik.cari');

Route::get('/anon', [AnonRoomController::class, 'createRoom'])->name('anon.create');
Route::post('/anon', [AnonRoomController::class, 'storeRoom'])->name('anon.store');

// untuk mengirim pesan (dibagikan ke orang lain)
Route::get('/anon/send/{kode_form}', [AnonMessageController::class, 'showForm'])->name('anon.send.form');
Route::post('/anon/send/{kode_form}', [AnonMessageController::class, 'store'])->name('anon.message.store');

// untuk melihat pesan (hanya pemilik room)
Route::get('/anon/{kode}', [AnonMessageController::class, 'showMessages'])->name('anon.show');