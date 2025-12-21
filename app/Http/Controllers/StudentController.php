<?php

namespace App\Http\Controllers;// Import model Student
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // Ambil semua data siswa dari database
        $students = Student::all();
        // Kirim data ke file view bernama 'students.index'
        return view('students.index', compact('students'));
    }
}