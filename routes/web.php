<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.app.dashboard');
});

Route::get('/page-kelas', function () {
    return view('pages.app.page-kelas');
})->name('page-kelas');

Route::get('/page-tugas', function () {
    return view('pages.app.page-tugas');
})->name('page-tugas');

Route::get('/form-tugas', function () {
    return view('pages.app.ceklis-tugas');
})->name('form-tugas');
