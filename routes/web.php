<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/students', [\App\Http\Controllers\StudentController::class, 'index']);
Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
// Halaman form tambah nilai
Route::get('/grades/create', [StudentController::class, 'createGrade'])->name('grades.create');
// Proses simpan nilai ke database
Route::post('/grades', [StudentController::class, 'storeGrade'])->name('grades.store');