<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventFormController;
use App\Http\Controllers\Admin\ManageEventController;
use App\Http\Controllers\Admin\OrmawaAccountController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik (bisa dibuka tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [EventController::class, 'home'])->name('home');
Route::get('/event', [EventController::class, 'index'])->name('events.index');
Route::get('/event/{event}', [EventController::class, 'show'])->whereNumber('event')->name('events.show');
Route::get('/faq', [EventController::class, 'faq'])->name('faq');

/*
|--------------------------------------------------------------------------
| Auth: login, registrasi + verifikasi kode, lupa password
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/registrasi', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registrasi', [AuthController::class, 'register']);
    Route::get('/verifikasi-kode', [AuthController::class, 'showVerification'])->name('verification.show');
    Route::post('/verifikasi-kode', [AuthController::class, 'verify'])->middleware('throttle:10,1')->name('verification.verify');
    Route::post('/verifikasi-kode/kirim-ulang', [AuthController::class, 'resendVerification'])->middleware('throttle:3,1')->name('verification.resend');

    Route::get('/lupa-password', [PasswordController::class, 'showRequest'])->name('password.request');
    Route::post('/lupa-password', [PasswordController::class, 'sendCode'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/lupa-password/kode', [PasswordController::class, 'showCode'])->name('password.code');
    Route::post('/lupa-password/kode', [PasswordController::class, 'verifyCode'])->middleware('throttle:10,1')->name('password.verify');
    Route::post('/lupa-password/kirim-ulang', [PasswordController::class, 'resend'])->middleware('throttle:3,1')->name('password.resend');
    Route::get('/lupa-password/baru', [PasswordController::class, 'showReset'])->name('password.reset');
    Route::post('/lupa-password/baru', [PasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pengaturan akun (semua role)
    Route::get('/akun', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/akun', [AccountController::class, 'update'])->name('account.update');
    Route::put('/akun/password', [AccountController::class, 'password'])->name('account.password');
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::get('/notifikasi/{id}/buka', [NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifikasi/{id}/baca', [NotificationController::class, 'read'])->name('notifications.read');
    Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    /*
    |----------------------------------------------------------------------
    | Mahasiswa: daftar event & event diikuti
    |----------------------------------------------------------------------
    */
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/event/{event}/daftar', [RegistrationController::class, 'create'])->name('registrations.create');
        Route::post('/event/{event}/daftar', [RegistrationController::class, 'store'])->name('registrations.store');
        Route::get('/event-diikuti', [RegistrationController::class, 'index'])->name('registrations.index');
        Route::get('/event-diikuti/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
        Route::get('/event-diikuti/{registration}/bukti', [RegistrationController::class, 'pdf'])->name('registrations.pdf');
        Route::post('/event-diikuti/{registration}/berkas', [RegistrationController::class, 'updateBerkas'])->name('registrations.berkas');
    });

    /*
    |----------------------------------------------------------------------
    | Admin Ormawa & Admin FMIPA: dashboard, ajukan/buat event, kelola event
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin_ormawa,admin_fmipa')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/jelajahi-event', [EventController::class, 'index'])->name('admin.events');

        Route::prefix('kelola-event')->name('manage.')->group(function () {
            Route::get('/', [ManageEventController::class, 'index'])->name('index');
            Route::get('/ajukan', [EventFormController::class, 'create'])->name('create');
            Route::post('/', [EventFormController::class, 'store'])->name('store');
            Route::get('/{event}', [ManageEventController::class, 'show'])->name('show');
            Route::get('/{event}/edit', [EventFormController::class, 'edit'])->name('edit');
            Route::put('/{event}', [EventFormController::class, 'update'])->name('update');
            Route::post('/{event}/seleksi', [ManageEventController::class, 'seleksi'])->name('seleksi');
            Route::post('/{event}/tahap', [ManageEventController::class, 'tahap'])->name('tahap');
            Route::get('/{event}/export', [ManageEventController::class, 'export'])->name('export');
            Route::get('/{event}/template-sertifikat', [ManageEventController::class, 'template'])->name('template');
            Route::post('/{event}/sertifikat', [ManageEventController::class, 'uploadSertifikat'])->name('sertifikat.store');
            Route::delete('/{event}/sertifikat/{sertifikat}', [ManageEventController::class, 'deleteSertifikat'])->name('sertifikat.destroy');
            Route::get('/{event}/pendaftar/{registration}', [ManageEventController::class, 'registrant'])->name('registrant');
            Route::post('/{event}/pendaftar/{registration}/keputusan', [ManageEventController::class, 'keputusan'])->name('keputusan');
            Route::get('/{event}/pendaftar/{registration}/zip', [ManageEventController::class, 'zip'])->name('zip');
            Route::get('/{event}/pendaftar/{registration}/bukti', [ManageEventController::class, 'bukti'])->name('bukti');
        });
    });

    /*
    |----------------------------------------------------------------------
    | Khusus Admin FMIPA: verifikasi event & kelola akun ormawa
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin_fmipa')->prefix('fmipa')->name('fmipa.')->group(function () {
        Route::get('/verifikasi', [VerificationController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{event}', [VerificationController::class, 'show'])->name('verifikasi.show');
        Route::post('/verifikasi/{event}/setujui', [VerificationController::class, 'approve'])->name('verifikasi.approve');
        Route::post('/verifikasi/{event}/revisi', [VerificationController::class, 'revise'])->name('verifikasi.revise');

        Route::get('/akun-ormawa', [OrmawaAccountController::class, 'index'])->name('ormawa.index');
        Route::post('/akun-ormawa', [OrmawaAccountController::class, 'store'])->name('ormawa.store');
        Route::put('/akun-ormawa/{account}', [OrmawaAccountController::class, 'update'])->name('ormawa.update');
    });
});
