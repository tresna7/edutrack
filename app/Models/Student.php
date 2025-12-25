<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['nis', 'name', 'class', 'academic_year', 'risk_level'];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // Fungsi untuk menghitung Rata-Rata Nilai
    public function getAverageScoreAttribute()
    {
        return $this->grades()->avg('score') ?? 0;
    }

    // Fungsi Logika Penentu Risiko Otomatis
    public function getAutomaticRiskLevelAttribute()
    {
        $average = $this->average_score;

        if ($average == 0) return 'Data Belum Lengkap';
        if ($average < 60) return 'Risiko Tinggi';
        if ($average <= 75) return 'Waspada';
        
        return 'Aman';
    }
}