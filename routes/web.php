<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/students', [\App\Http\Controllers\StudentController::class, 'index']);
Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');