<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'latitude',
        'longitude',
        'attendance_radius'
    ];

    protected $casts = [
        'attendance_radius' => 'integer',
    ];
}
