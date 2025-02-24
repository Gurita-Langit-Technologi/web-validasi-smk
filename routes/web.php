<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\RekapTugasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login-form');

Route::middleware(['auth:guru'])->group(function () {
    Route::get('/guru', function () {
        return view('pages.app.dashboard');
    })->name('guru.dashboard');
});

Route::get('/guru/create-password', [GuruController::class, 'create'])->name('guru.create');
Route::post('/guru/create-password', [GuruController::class, 'store'])->name('guru.set-password');



Route::get('/page-kelas', function () {
    return view('pages.app.page-kelas');
})->name('page-kelas');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/form-tugas/{id_mapel}', [RekapTugasController::class, 'showDetailTugas'])->name('detail-tugas');
Route::get('/page-tugas', [RekapTugasController::class, 'index'])->name('page-tugas');
Route::get('/rekap-tugas/edit/{id}', [RekapTugasController::class, 'edit'])->name('rekap-tugas.edit');
Route::post('/rekap-tugas/update/{id}', [RekapTugasController::class, 'update'])->name('rekap-tugas.update');
Route::get('/page-kelas', [KelasController::class, 'index'])->name('page-kelas');
// Route::get('/form-tugas/{id}', [TugasController::class, 'show'])->name('form-tugas');
Route::post('/generate-tasks-per-class/{id_rekap}', [RekapTugasController::class, 'generateTasksPerClass']);
Route::post('/generate-all-tasks', [RekapTugasController::class, 'generateAllTasks']);
