<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\RiskAnalysisController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AttendanceController;

// Homepage dengan pilihan kelas
Route::get('/', [HomeController::class, 'index'])->name('home');

// Students - All or by class
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/class/{class}', [StudentController::class, 'index'])->name('students.by.class');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

// Grades Resource
Route::resource('grades', GradeController::class);

// Subjects CRUD
Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
Route::get('/subjects/{id}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

// Attendance Management
Route::prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('/create', [AttendanceController::class, 'create'])->name('create');
    Route::post('/store', [AttendanceController::class, 'store'])->name('store');
    
    // QR Code (Phase 3)
    Route::get('/qr-generate', [AttendanceController::class, 'generateQR'])->name('qr.generate');
    Route::get('/qr-scan', [AttendanceController::class, 'scanQR'])->name('qr.scan');
    Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('checkin');
    
    // Reports (Phase 4)
    Route::get('/report', [AttendanceController::class, 'report'])->name('report');
});

// Risk Analysis
Route::get('/risk-analysis', [RiskAnalysisController::class, 'index'])->name('risk.analysis');
