@extends('layouts.layout')

@section('title', 'Dashboard Absensi - EduTrack')

@section('content')
<div class="fade-in-up">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-calendar-check me-2"></i>Dashboard Absensi</h4>
            <p class="text-muted mb-0">Monitoring kehadiran siswa</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('attendance.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Input Absensi
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card shadow-soft mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-8 d-flex align-items-end">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Menampilkan data absensi untuk tanggal <strong>{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</strong>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <div class="card shadow-soft border-0">
                <div class="card-body text-center">
                    <div class="fs-1 text-primary mb-2"><i class="bi bi-people-fill"></i></div>
                    <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                    <p class="text-muted mb-0 small">Total Siswa</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card shadow-soft border-0 border-start border-success border-4">
                <div class="card-body text-center">
                    <div class="fs-1 text-success mb-2"><i class="bi bi-check-circle-fill"></i></div>
                    <h3 class="mb-0 fw-bold text-success">{{ $stats['hadir'] }}</h3>
                    <p class="text-muted mb-0 small">Hadir</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card shadow-soft border-0 border-start border-warning border-4">
                <div class="card-body text-center">
                    <div class="fs-1 text-warning mb-2"><i class="bi bi-heart-pulse-fill"></i></div>
                    <h3 class="mb-0 fw-bold text-warning">{{ $stats['sakit'] }}</h3>
                    <p class="text-muted mb-0 small">Sakit</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card shadow-soft border-0 border-start border-info border-4">
                <div class="card-body text-center">
                    <div class="fs-1 text-info mb-2"><i class="bi bi-envelope-paper-fill"></i></div>
                    <h3 class="mb-0 fw-bold text-info">{{ $stats['izin'] }}</h3>
                    <p class="text-muted mb-0 small">Izin</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card shadow-soft border-0 border-start border-danger border-4">
                <div class="card-body text-center">
                    <div class="fs-1 text-danger mb-2"><i class="bi bi-x-circle-fill"></i></div>
                    <h3 class="mb-0 fw-bold text-danger">{{ $stats['alpa'] }}</h3>
                    <p class="text-muted mb-0 small">Alpa</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card shadow-soft border-0 border-start border-secondary border-4">
                <div class="card-body text-center">
                    <div class="fs-1 text-secondary mb-2"><i class="bi bi-question-circle-fill"></i></div>
                    <h3 class="mb-0 fw-bold text-secondary">{{ $stats['belum_absen'] }}</h3>
                    <p class="text-muted mb-0 small">Belum Absen</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance List -->
    <div class="card shadow-soft">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Daftar Absensi</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Waktu</th>
                            <th class="text-center">Metode</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $attendance)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        {{ substr($attendance->student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $attendance->student->name }}</div>
                                        <small class="text-muted">NIS: {{ $attendance->student->nis }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info">{{ $attendance->student->class }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $badgeClass = match($attendance->status) {
                                        'Hadir' => 'success',
                                        'Sakit' => 'warning',
                                        'Izin' => 'info',
                                        'Alpa' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}-subtle text-{{ $badgeClass }}">
                                    {{ $attendance->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($attendance->check_in_time)
                                    <span class="text-muted small">
                                        {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary">
                                    {{ $attendance->method == 'manual' ? 'Manual' : 'QR Code' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small">{{ $attendance->notes ?? '-' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">Belum ada data absensi untuk tanggal ini</p>
                                <a href="{{ route('attendance.create', ['date' => $date]) }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="bi bi-plus-circle me-1"></i> Input Absensi Sekarang
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
    font-weight: 600;
}
</style>
@endsection
