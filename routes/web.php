<?php

use App\Exports\RekapTugasExport;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\RekapTugasController;
use App\Http\Controllers\WaliController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::prefix('guru')->group(function () {
    Route::get('/login', [AuthController::class, 'showGuruLoginForm'])->name('login.guru.form');
    Route::post('/login', [AuthController::class, 'loginGuru'])->name('login.guru');
});

Route::prefix('koordinator')->group(function () {
    Route::get('/login', [AuthController::class, 'showKoordinatorLoginForm'])->name('login.koordinator.form');
    Route::post('/login', [AuthController::class, 'loginKoordinator'])->name('login.koordinator');
});


// Login untuk Wali Kelas
Route::prefix('wali')->group(function () {
    Route::get('/login', [AuthController::class, 'showWaliLoginForm'])->name('login.wali.form');
    Route::post('/login', [AuthController::class, 'loginWali'])->name('login.wali');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password-form');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('forgot-password');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('reset-password-form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

Route::middleware(['auth:guru'])->prefix('guru')->group(function () {
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard');
    Route::get('/form-tugas/{id_kelas}', [RekapTugasController::class, 'showDetailTugas'])->name('detail-tugas');
    Route::get('/page-tugas', [RekapTugasController::class, 'index'])->name('page-tugas');
    Route::get('/rekap-tugas/edit/{id}', [RekapTugasController::class, 'edit'])->name('rekap-tugas.edit');
    Route::post('/rekap-tugas/update/{id}', [RekapTugasController::class, 'update'])->name('rekap-tugas.update');
    Route::get('/page-kelas', [KelasController::class, 'index'])->name('page-kelas');
    // Route::get('/form-tugas/{id}', [TugasController::class, 'show'])->name('form-tugas');
    Route::post('/generate-tasks-per-class/{id_rekap}', [RekapTugasController::class, 'generateTasksPerClass']);
    Route::post('/generate-all-tasks', [RekapTugasController::class, 'generateAllTasks']);
    Route::post('/add-single-task/{id_rekap}', [RekapTugasController::class, 'addSingleTaskPerClass']);
    Route::post('/update-status-tugas', [RekapTugasController::class, 'updateStatusTugas'])->name('update-status-tugas');
    Route::get('/export-tugas/{id_mapel}', function ($id_mapel) {
        return Excel::download(new RekapTugasExport($id_mapel), 'rekap_tugas.xlsx');
    })->name('export-tugas');
});

Route::middleware(['auth:wali'])->prefix('wali')->group(function () {
    Route::get('/dashboard', [WaliController::class, 'dashboard'])->name('wali.dashboard');
});

Route::get('/koordinator/dashboard', [KoordinatorController::class, 'dashboard'])->name('koordinator.dashboard');

Route::get('/guru/create-password', [GuruController::class, 'create'])->name('guru.create');
Route::post('/guru/create-password', [GuruController::class, 'store'])->name('guru.set-password');

// Route::get('/wali/create-password', [WaliController::class, 'create'])->name('wali.create');
// Route::post('/wali/create-password', [WaliController::class, 'store'])->name('wali.set-password');
