<?php

use App\Exports\RekapTugasExport;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\RekapTugasController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
});


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password-form');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('forgot-password');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('reset-password-form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

Route::middleware(['auth:guru'])->group(function () {
    Route::get('/guru', function () {
        return view('pages.app.dashboard');
    })->name('guru.dashboard');
    Route::get('/form-tugas/{id_mapel}', [RekapTugasController::class, 'showDetailTugas'])->name('detail-tugas');
    Route::get('/page-tugas', [RekapTugasController::class, 'index'])->name('page-tugas');
    Route::get('/rekap-tugas/edit/{id}', [RekapTugasController::class, 'edit'])->name('rekap-tugas.edit');
    Route::post('/rekap-tugas/update/{id}', [RekapTugasController::class, 'update'])->name('rekap-tugas.update');
    Route::get('/page-kelas', [KelasController::class, 'index'])->name('page-kelas');
    // Route::get('/form-tugas/{id}', [TugasController::class, 'show'])->name('form-tugas');
    Route::post('/generate-tasks-per-class/{id_rekap}', [RekapTugasController::class, 'generateTasksPerClass']);
    Route::post('/generate-all-tasks', [RekapTugasController::class, 'generateAllTasks']);
    Route::post('/update-status-tugas', [RekapTugasController::class, 'updateStatusTugas'])->name('update-status-tugas');
    Route::get('/export-tugas/{id_mapel}', function ($id_mapel) {
        return Excel::download(new RekapTugasExport($id_mapel), 'rekap_tugas.xlsx');
    })->name('export-tugas');
});

Route::get('/guru/create-password', [GuruController::class, 'create'])->name('guru.create');
Route::post('/guru/create-password', [GuruController::class, 'store'])->name('guru.set-password');

//test cicd
Route::get('/test', function () {
    return "Hello World";
});
