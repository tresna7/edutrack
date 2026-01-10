<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'date',
        'status',
        'method',
        'check_in_time',
        'latitude',
        'longitude',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime:H:i',
    ];

    /**
     * Relationship: Attendance belongs to Student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope: Get today's attendance
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * Scope: Get attendance by date
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope: Get attendance by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if student checked in late
     * Assuming school starts at 7:30 AM
     */
    public function isLate(): bool
    {
        if (!$this->check_in_time) {
            return false;
        }

        $schoolStartTime = '07:30:00';
        return $this->check_in_time > $schoolStartTime;
    }

    /**
     * Check if coordinates are within school radius
     */
    public static function isWithinRadius($studentLat, $studentLon, $schoolLat, $schoolLon, $radius): bool
    {
        $earthRadius = 6371000; // meters
        
        $latDiff = deg2rad($schoolLat - $studentLat);
        $lonDiff = deg2rad($schoolLon - $studentLon);
        
        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($studentLat)) * cos(deg2rad($schoolLat)) *
             sin($lonDiff / 2) * sin($lonDiff / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;
        
        return $distance <= $radius;
    }
}
