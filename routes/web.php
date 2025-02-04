<?php

use App\Http\Controllers\KelasController;
use App\Http\Controllers\RekapTugasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.app.dashboard');
});

Route::get('/page-kelas', function () {
    return view('pages.app.page-kelas');
})->name('page-kelas');

// Route::get('/page-tugas', function () {
//     return view('pages.app.page-tugas');
// })->name('page-tugas');

// Route::get('/form-tugas', function () {
//     return view('pages.app.ceklis-tugas');
// })->name('form-tugas');

Route::get('/form-tugas/{id_mapel}', [RekapTugasController::class, 'showDetailTugas'])->name('detail-tugas');
Route::get('/page-tugas', [RekapTugasController::class, 'index'])->name('page-tugas');
Route::get('/rekap-tugas/edit/{id}', [RekapTugasController::class, 'edit'])->name('rekap-tugas.edit');
Route::post('/rekap-tugas/update/{id}', [RekapTugasController::class, 'update'])->name('rekap-tugas.update');
Route::get('/page-kelas', [KelasController::class, 'index'])->name('page-kelas');
// Route::get('/form-tugas/{id}', [TugasController::class, 'show'])->name('form-tugas');
