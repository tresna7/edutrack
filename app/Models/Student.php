<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function grades()
        {
            // Menghubungkan Student ke Grade
            return $this->hasMany(Grade::class);
        }// Izinkan kolom-kolom ini diisi secara otomatis
    
}
