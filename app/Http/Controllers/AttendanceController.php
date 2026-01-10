<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance dashboard
     */
    public function index(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        
        // Get attendance statistics for the selected date
        $attendances = Attendance::with('student')
            ->byDate($date)
            ->get();
        
        $stats = [
            'total' => Student::count(),
            'hadir' => $attendances->where('status', 'Hadir')->count(),
            'sakit' => $attendances->where('status', 'Sakit')->count(),
            'izin' => $attendances->where('status', 'Izin')->count(),
            'alpa' => $attendances->where('status', 'Alpa')->count(),
            'belum_absen' => Student::count() - $attendances->count(),
        ];
        
        // Weekly trend data (last 7 days)
        $weeklyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $dayAttendances = Attendance::byDate($day->format('Y-m-d'))->get();
            
            $weeklyTrend[] = [
                'date' => $day->format('D'),
                'hadir' => $dayAttendances->where('status', 'Hadir')->count(),
                'sakit' => $dayAttendances->where('status', 'Sakit')->count(),
                'izin' => $dayAttendances->where('status', 'Izin')->count(),
                'alpa' => $dayAttendances->where('status', 'Alpa')->count(),
            ];
        }
        
        // Low attendance alert (5+ Alpa in last 30 days)
        $lowAttendanceStudents = Student::withCount([
            'attendances as alpa_count' => function($q) {
                $q->where('date', '>=', now()->subDays(30))
                  ->where('status', 'Alpa');
            }
        ])->having('alpa_count', '>=', 3) // Changed to 3 for demo
          ->orderBy('alpa_count', 'desc')
          ->limit(10)
          ->get();
        
        // Monthly comparison
        $thisMonth = Attendance::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();
        
        $lastMonth = Attendance::whereMonth('date', now()->subMonth()->month)
            ->whereYear('date', now()->subMonth()->year)
            ->get();
        
        $monthlyComparison = [
            'this_month' => [
                'hadir' => $thisMonth->where('status', 'Hadir')->count(),
                'sakit' => $thisMonth->where('status', 'Sakit')->count(),
                'izin' => $thisMonth->where('status', 'Izin')->count(),
                'alpa' => $thisMonth->where('status', 'Alpa')->count(),
            ],
            'last_month' => [
                'hadir' => $lastMonth->where('status', 'Hadir')->count(),
                'sakit' => $lastMonth->where('status', 'Sakit')->count(),
                'izin' => $lastMonth->where('status', 'Izin')->count(),
                'alpa' => $lastMonth->where('status', 'Alpa')->count(),
            ]
        ];
        
        return view('attendances.index', compact(
            'attendances', 
            'stats', 
            'date', 
            'weeklyTrend', 
            'lowAttendanceStudents',
            'monthlyComparison'
        ));
    }

    /**
     * Show manual attendance form
     */
    public function create(Request $request)
    {
        $class = $request->get('class');
        $date = $request->get('date', today()->format('Y-m-d'));
        
        // Get students by class
        $query = Student::orderBy('name');
        if ($class) {
            $query->where('class', $class);
        }
        $students = $query->get();
        
        // Get existing attendance for the date
        $existingAttendance = Attendance::byDate($date)
            ->pluck('status', 'student_id')
            ->toArray();
        
        // Get all unique classes
        $classes = Student::select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');
        
        return view('attendances.create', compact('students', 'existingAttendance', 'classes', 'class', 'date'));
    }

    /**
     * Store manual attendance
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        $date = $request->date;
        $successCount = 0;
        $updateCount = 0;

        foreach ($request->attendances as $attendanceData) {
            $attendance = Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'date' => $date,
                ],
                [
                    'status' => $attendanceData['status'],
                    'method' => 'manual',
                    'check_in_time' => now()->format('H:i:s'),
                    'notes' => $attendanceData['notes'] ?? null,
                ]
            );

            if ($attendance->wasRecentlyCreated) {
                $successCount++;
            } else {
                $updateCount++;
            }
        }

        $message = "Absensi berhasil disimpan! ($successCount baru, $updateCount diupdate)";
        
        return redirect()->route('attendance.index', ['date' => $date])
            ->with('success', $message);
    }

    /**
     * Generate QR Code for attendance
     */
    public function generateQR()
    {
        $today = today()->format('Y-m-d');
        $timestamp = now()->timestamp;
        $validUntil = now()->addMinutes(30)->timestamp; // Valid for 30 minutes
        
        // Create signature to prevent tampering
        $secret = config('app.key');
        $signature = hash_hmac('sha256', $today . $timestamp, $secret);
        
        $qrData = [
            'type' => 'attendance',
            'date' => $today,
            'timestamp' => $timestamp,
            'valid_until' => $validUntil,
            'signature' => $signature
        ];
        
        $qrString = json_encode($qrData);
        
        // Get school GPS coordinates
        $school = \App\Models\School::first();
        
        return view('attendances.qr-generate', compact('qrString', 'school', 'validUntil'));
    }

    /**
     * Show QR scan page
     */
    public function scanQR()
    {
        $students = Student::orderBy('name')->get();
        return view('attendances.qr-scan', compact('students'));
    }

    /**
     * Process QR check-in
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
            'student_id' => 'required|exists:students,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        
        // 1. Decode QR data
        $qrData = json_decode($request->qr_data, true);
        
        if (!$qrData || !isset($qrData['signature'])) {
            return response()->json(['success' => false, 'message' => 'QR Code tidak valid!'], 400);
        }
        
        // 2. Verify signature
        $secret = config('app.key');
        $expectedSignature = hash_hmac('sha256', $qrData['date'] . $qrData['timestamp'], $secret);
        
        if ($qrData['signature'] !== $expectedSignature) {
            return response()->json(['success' => false, 'message' => 'QR Code tidak valid (signature mismatch)!'], 400);
        }
        
        // 3. Check time window
        $now = now()->timestamp;
        if ($now > $qrData['valid_until']) {
            return response()->json(['success' => false, 'message' => 'QR Code sudah kadaluarsa!'], 400);
        }
        
        // 4. Check if already checked in today
        $existingAttendance = Attendance::where('student_id', $request->student_id)
            ->where('date', $qrData['date'])
            ->first();
        
        if ($existingAttendance) {
            return response()->json(['success' => false, 'message' => 'Anda sudah absen hari ini!'], 400);
        }
        
        // 5. Validate GPS location
        $school = \App\Models\School::first();
        
        if (!$school || !$school->latitude || !$school->longitude) {
            return response()->json(['success' => false, 'message' => 'Koordinat sekolah belum diatur!'], 400);
        }
        
        $isWithinRadius = Attendance::isWithinRadius(
            $request->latitude,
            $request->longitude,
            $school->latitude,
            $school->longitude,
            $school->attendance_radius
        );
        
        if (!$isWithinRadius) {
            return response()->json(['success' => false, 'message' => 'Anda berada di luar area sekolah!'], 400);
        }
        
        // 6. Create attendance record
        $attendance = Attendance::create([
            'student_id' => $request->student_id,
            'date' => $qrData['date'],
            'status' => 'Hadir',
            'method' => 'qr_code',
            'check_in_time' => now()->format('H:i:s'),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        
        $student = Student::find($request->student_id);
        
        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil! Selamat belajar, ' . $student->name . '!',
            'data' => $attendance
        ]);
    }

    /**
     * Show attendance report
     */
    public function report(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $class = $request->get('class');
        
        // Per-student attendance
        $query = Student::with(['attendances' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('date', [$startDate, $endDate]);
        }]);
        
        if ($class) {
            $query->where('class', $class);
        }
        
        $students = $query->get()->map(function($student) {
            $attendances = $student->attendances;
            $total = $attendances->count();
            
            return [
                'name' => $student->name,
                'class' => $student->class,
                'total' => $total,
                'hadir' => $attendances->where('status', 'Hadir')->count(),
                'sakit' => $attendances->where('status', 'Sakit')->count(),
                'izin' => $attendances->where('status', 'Izin')->count(),
                'alpa' => $attendances->where('status', 'Alpa')->count(),
                'rate' => $total > 0 ? round(($attendances->where('status', 'Hadir')->count() / $total) * 100, 1) : 0
            ];
        });
        
        // Per-class summary
        $classes = Student::select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');
        
        $classSummary = $classes->map(function($className) use ($startDate, $endDate) {
            $classStudents = Student::where('class', $className)->pluck('id');
            $attendances = Attendance::whereIn('student_id', $classStudents)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();
            
            $total = $attendances->count();
            
            return [
                'class' => $className,
                'total' => $total,
                'hadir' => $attendances->where('status', 'Hadir')->count(),
                'sakit' => $attendances->where('status', 'Sakit')->count(),
                'izin' => $attendances->where('status', 'Izin')->count(),
                'alpa' => $attendances->where('status', 'Alpa')->count(),
                'rate' => $total > 0 ? round(($attendances->where('status', 'Hadir')->count() / $total) * 100, 1) : 0
            ];
        });
        
        $allClasses = Student::select('class')->distinct()->orderBy('class')->pluck('class');
        
        return view('attendances.report', compact(
            'students', 
            'classSummary', 
            'startDate', 
            'endDate', 
            'class',
            'allClasses'
        ));
    }
}
