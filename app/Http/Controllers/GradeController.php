<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Grade::with(['student', 'subject']);
        
        // Search by student name or subject
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('subject', function($q) use ($search) {
                $q->where('subject_name', 'like', "%{$search}%");
            });
        }
        
        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        
        // Paginate results
        $grades = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        return view('grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::orderBy('name')->get();
        $subjects = Subject::orderBy('subject_name')->get();
        
        return view('grades.create', compact('students', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|numeric|min:0|max:100',
            'semester' => 'required|in:1,2'
        ]);

        Grade::create($request->all());

        return redirect()->route('grades.index')->with('success', 'Nilai berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $grade = Grade::findOrFail($id);
        $students = Student::orderBy('name')->get();
        $subjects = Subject::orderBy('subject_name')->get();
        
        return view('grades.edit', compact('grade', 'students', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|numeric|min:0|max:100',
            'semester' => 'required|in:1,2'
        ]);

        $grade = Grade::findOrFail($id);
        $grade->update($request->all());

        return redirect()->route('grades.index')->with('success', 'Nilai berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return redirect()->route('grades.index')->with('success', 'Nilai berhasil dihapus!');
    }
}
