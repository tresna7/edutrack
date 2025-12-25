<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Student; 
use App\Models\Grade;
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
        $student = Student::with('grades.subject')->findOrFail($id);
        return view('students.show', compact('student'));
    }

    // --- TAMBAHKAN KODE DI BAWAH INI ---

    public function createGrade()
    {
        // Mengambil semua data siswa dan mapel untuk isi dropdown di form
        $students = Student::all();
        $subjects = Subject::all();
        
        return view('grades.create', compact('students', 'subjects'));
    }

    public function storeGrade(Request $request)
    {
        // Validasi agar data yang masuk tidak sembarangan
        $request->validate([
            'student_id' => 'required',
            'subject_id' => 'required',
            'score'      => 'required|numeric|min:0|max:100',
            'semester'   => 'required'
        ]);

        // Simpan ke database
        Grade::create($request->all());

        // Setelah simpan, balikkan ke halaman daftar siswa dengan pesan sukses
        return redirect('/students')->with('success', 'Nilai berhasil ditambahkan!');
    }
}