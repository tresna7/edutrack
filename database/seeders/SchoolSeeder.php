<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        School::create([
            'name' => 'SMA Negeri 1 Jakarta',
            'address' => 'Jl. Budi Utomo No. 7, Pasar Baru, Jakarta Pusat',
            'phone' => '021-3441621',
            'email' => 'info@sman1jakarta.sch.id',
            // Coordinates for SMAN 1 Jakarta (example)
            'latitude' => '-6.168730',
            'longitude' => '106.835190',
            'attendance_radius' => 100, // 100 meters
        ]);
    }
}
