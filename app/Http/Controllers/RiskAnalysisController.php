<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class RiskAnalysisController extends Controller
{
    public function index()
    {
        $students = Student::with('grades')->get();
        
        // Statistik risiko
        $totalStudents = $students->count();
        $safeCount = $students->filter(fn($s) => $s->automatic_risk_level == 'Aman')->count();
        $warningCount = $students->filter(fn($s) => $s->automatic_risk_level == 'Waspada')->count();
        $highRiskCount = $students->filter(fn($s) => $s->automatic_risk_level == 'Risiko Tinggi')->count();
        
        // Data untuk chart distribusi risiko
        $riskDistribution = [
            'labels' => ['Aman', 'Waspada', 'Risiko Tinggi'],
            'data' => [$safeCount, $warningCount, $highRiskCount]
        ];
        
        // Rata-rata nilai per kelas
        $classesList = $students->pluck('class')->unique()->values();
        $averageByClass = [];
        foreach ($classesList as $class) {
            $classStudents = $students->where('class', $class);
            $avgScore = $classStudents->avg('average_score');
            $averageByClass[$class] = round($avgScore, 2);
        }
        
        return view('risk.index', compact(
            'totalStudents',
            'safeCount',
            'warningCount',
            'highRiskCount',
            'riskDistribution',
            'averageByClass',
            'students'
        ));
    }
}
