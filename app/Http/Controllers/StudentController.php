<?php

namespace App\Http\Controllers;

use App\Models\Student; // Import model Student
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    public function show($id)
    {
        // Kode ini sudah benar, asalkan relasi di Model sudah dibuat
        $student = Student::with('grades.subject')->findOrFail($id);
        return view('students.show', compact('student'));
    }
}