<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Grade::create([
            'student_id' => 1,
            'subject_id' => 1,
            'score' => 45,
            'semester' => 1,
        ]);
        \App\Models\Grade::create([
            'student_id' => 1,
            'subject_id' => 2,
            'score' => 75,
            'semester' => 2,
        ]);
        \App\Models\Grade::create([
            'student_id' => 1,
            'subject_id' => 4,
            'score' => 85,
            'semester' => 1,
        ]);
    }
}
