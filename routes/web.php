<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuratController;
use App\Http\Controllers\AnonRoomController;
use App\Http\Controllers\AnonMessageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnonChatController;

Route::get('/', [SuratController::class, 'index']);
Route::get('/index', [SuratController::class, 'index']);

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard & Top-Up Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('dashboard');
    Route::get('/topup', [DashboardController::class, 'showTopupForm'])->name('topup.form');
    Route::post('/topup', [DashboardController::class, 'processTopup'])->name('topup.process');
    
    Route::get('/admin', [DashboardController::class, 'adminIndex'])->name('admin');
    Route::post('/admin/approve/{id}', [DashboardController::class, 'approvePayment'])->name('admin.approve');
    Route::post('/admin/package/{id}', [DashboardController::class, 'updatePackage'])->name('admin.package.update');
    Route::post('/admin/user/{id}/credits', [DashboardController::class, 'updateUserCredits'])->name('admin.user.credits');

    // Surat Creation
    Route::get('/create', [SuratController::class, 'create'])->name('create');
    Route::post('/surat', [SuratController::class, 'store']);
    Route::post('/surat/{id}/update-password', [SuratController::class, 'updatePassword'])->name('surat.update-password');
});

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

// untuk menghapus pesan anonim
Route::delete('/anon/message/{id}', [AnonMessageController::class, 'delete'])->name('anon.message.delete');

// Fitur Obrolan Anonim (Gender Match Chat)
Route::get('/anon-chat', [AnonChatController::class, 'index'])->name('anon-chat.index');
Route::post('/anon-chat/join', [AnonChatController::class, 'joinQueue']);
Route::get('/anon-chat/status', [AnonChatController::class, 'checkQueueStatus']);
Route::post('/anon-chat/leave-queue', [AnonChatController::class, 'leaveQueue']);
Route::get('/anon-chat/messages', [AnonChatController::class, 'getMessages']);
Route::post('/anon-chat/send', [AnonChatController::class, 'sendMessage']);
Route::post('/anon-chat/leave', [AnonChatController::class, 'endChat']);