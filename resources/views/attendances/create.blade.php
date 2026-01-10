@extends('layouts.layout')

@section('title', 'Input Absensi Manual - EduTrack')

@section('content')
<div class="fade-in-up">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Absensi</a></li>
                <li class="breadcrumb-item active">Input Manual</li>
            </ol>
        </nav>
        <h4 class="mb-1"><i class="bi bi-pencil-square me-2"></i>Input Absensi Manual</h4>
        <p class="text-muted mb-0">Input atau update absensi siswa secara manual</p>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-soft mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.create') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control" value="{{ $date }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <select name="class" class="form-select">
                        <option value="">Semua Kelas</option>
                        @if(is_array($classes) || is_object($classes))
                            @foreach($classes as $cls)
                                <option value="{{ $cls }}" {{ $class == $cls ? 'selected' : '' }}>{{ $cls }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($students->count() > 0)
    <!-- Attendance Form -->
    <form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">
        
        <div class="card shadow-soft">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-list-check me-2"></i>
                        Daftar Siswa 
                        @if($class)
                            <span class="badge bg-info-subtle text-info">{{ $class }}</span>
                        @endif
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success" onclick="markAllAs('Hadir')">
                            <i class="bi bi-check-all me-1"></i> Hadir Semua
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="markAllAs('Alpa')">
                            <i class="bi bi-x-circle me-1"></i> Alpa Semua
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetForm()">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60">No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th width="200">Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                            <small class="text-muted">NIS: {{ $student->nis }}</small>
                                        </div>
                                    </div>
                                    <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">{{ $student->class }}</span>
                                </td>
                                <td>
                                    <select name="attendances[{{ $index }}][status]" 
                                            class="form-select form-select-sm status-select" 
                                            data-index="{{ $index }}"
                                            required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Hadir" {{ isset($existingAttendance[$student->id]) && $existingAttendance[$student->id] == 'Hadir' ? 'selected' : '' }}>
                                            Hadir
                                        </option>
                                        <option value="Sakit" {{ isset($existingAttendance[$student->id]) && $existingAttendance[$student->id] == 'Sakit' ? 'selected' : '' }}>
                                            Sakit
                                        </option>
                                        <option value="Izin" {{ isset($existingAttendance[$student->id]) && $existingAttendance[$student->id] == 'Izin' ? 'selected' : '' }}>
                                            Izin
                                        </option>
                                        <option value="Alpa" {{ isset($existingAttendance[$student->id]) && $existingAttendance[$student->id] == 'Alpa' ? 'selected' : '' }}>
                                            Alpa
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" 
                                           name="attendances[{{ $index }}][notes]" 
                                           class="form-control form-control-sm" 
                                           placeholder="Keterangan (opsional)">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Total <strong>{{ $students->count() }}</strong> siswa
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan Absensi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @else
    <!-- No Students -->
    <div class="card shadow-soft">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">Tidak ada siswa</h5>
            <p class="text-muted mb-0">
                @if($class)
                    Tidak ada siswa di kelas {{ $class }}
                @else
                    Silakan pilih kelas terlebih dahulu
                @endif
            </p>
        </div>
    </div>
    @endif
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
    font-weight: 600;
}

.status-select {
    min-width: 150px;
}
</style>

<script>
// Mark all students with specific status
function markAllAs(status) {
    const selects = document.querySelectorAll('.status-select');
    selects.forEach(select => {
        select.value = status;
    });
}

// Reset form
function resetForm() {
    if (confirm('Reset semua pilihan status?')) {
        const selects = document.querySelectorAll('.status-select');
        selects.forEach(select => {
            select.value = '';
        });
        
        const notes = document.querySelectorAll('input[name*="[notes]"]');
        notes.forEach(note => {
            note.value = '';
        });
    }
}

// Form validation
document.getElementById('attendanceForm')?.addEventListener('submit', function(e) {
    const selects = document.querySelectorAll('.status-select');
    let hasSelection = false;
    
    selects.forEach(select => {
        if (select.value) {
            hasSelection = true;
        }
    });
    
    if (!hasSelection) {
        e.preventDefault();
        alert('Pilih minimal 1 siswa untuk diabsen!');
        return false;
    }
});
</script>
@endsection
