<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get total students count
        $totalStudents = Student::count();
        
        // Get top students (highest average scores)
        $topStudents = Student::with('grades')
            ->get()
            ->sortByDesc('average_score')
            ->take(5);
        
        return view('home', compact('totalStudents', 'topStudents'));
    }
}
