<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Student::create([
            'nis' => '1234',
            'name' => 'Budi Santoso',
            'class' => '12-IPA-1',
            'academic_year' => '2024/2025',
            'risk_level' => 'Aman',
        ]);
    }
}
