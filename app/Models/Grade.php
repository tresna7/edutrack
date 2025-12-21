<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    // Izinkan kolom-kolom ini diisi secara otomatis
    protected $fillable = ['student_id', 'subject_id', 'score', 'semester'];

    /**
     * Relasi ke Model Student
     * Satu nilai dimiliki oleh satu siswa
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relasi ke Model Subject
     * Satu nilai merujuk ke satu mata pelajaran
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
