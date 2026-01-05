@extends('layouts.layout')

@section('title', 'Analisis Risiko - EduTrack')

@section('extra_css')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="fade-in-up">
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold text-gradient-primary">
            <i class="bi bi-graph-up-arrow me-2"></i>Dashboard Analisis Risiko
        </h2>
        <p class="text-muted">Visualisasi dan analisis risiko putus sekolah siswa</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-primary text-white shadow-soft">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill fs-1 mb-2"></i>
                    <h6 class="mb-1">Total Siswa</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalStudents }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-success text-white shadow-soft">
                <div class="card-body text-center">
                    <i class="bi bi-shield-check fs-1 mb-2"></i>
                    <h6 class="mb-1">Kondisi Aman</h6>
                    <h2 class="mb-0 fw-bold">{{ $safeCount }}</h2>
                    <small>{{ $totalStudents > 0 ? round(($safeCount / $totalStudents) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-warning text-white shadow-soft">
                <div class="card-body text-center">
                    <i class="bi bi-info-circle-fill fs-1 mb-2"></i>
                    <h6 class="mb-1">Waspada</h6>
                    <h2 class="mb-0 fw-bold">{{ $warningCount }}</h2>
                    <small>{{ $totalStudents > 0 ? round(($warningCount / $totalStudents) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-danger text-white shadow-soft">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle-fill fs-1 mb-2"></i>
                    <h6 class="mb-1">Risiko Tinggi</h6>
                    <h2 class="mb-0 fw-bold">{{ $highRiskCount }}</h2>
                    <small>{{ $totalStudents > 0 ? round(($highRiskCount / $totalStudents) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow-soft">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-pie-chart me-2 text-gradient-primary"></i>Distribusi Status Risiko
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="riskDistributionChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-soft">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart me-2 text-gradient-primary"></i>Rata-rata Nilai per Kelas
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="averageByClassChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Students at Risk Table -->
    <div class="card shadow-soft">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Siswa Berisiko Tinggi
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th class="text-center">Rata-rata Nilai</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $highRiskStudents = $students->filter(fn($s) => $s->automatic_risk_level == 'Risiko Tinggi');
                        @endphp
                        
                        @forelse($highRiskStudents as $student)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">{{ $student->nis }}</td>
                            <td class="fw-bold">{{ $student->name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $student->class }}</span></td>
                            <td class="text-center">
                                <span class="badge bg-danger fs-6">{{ number_format($student->average_score, 1) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger px-3">
                                    <i class="bi bi-exclamation-octagon-fill me-1"></i> Risiko Tinggi
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-info btn-sm border-0">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle fs-1 text-success d-block mb-2"></i>
                                Tidak ada siswa dengan risiko tinggi. Semua siswa dalam kondisi baik!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
// Risk Distribution Pie Chart
const riskCtx = document.getElementById('riskDistributionChart').getContext('2d');
new Chart(riskCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($riskDistribution['labels']) !!},
        datasets: [{
            data: {!! json_encode($riskDistribution['data']) !!},
            backgroundColor: [
                'rgba(17, 153, 142, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(235, 51, 73, 0.8)'
            ],
            borderColor: [
                'rgba(17, 153, 142, 1)',
                'rgba(255, 193, 7, 1)',
                'rgba(235, 51, 73, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12,
                        family: 'Inter'
                    }
                }
            }
        }
    }
});

// Average by Class Bar Chart
const classCtx = document.getElementById('averageByClassChart').getContext('2d');
new Chart(classCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($averageByClass)) !!},
        datasets: [{
            label: 'Rata-rata Nilai',
            data: {!! json_encode(array_values($averageByClass)) !!},
            backgroundColor: 'rgba(102, 126, 234, 0.8)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    font: {
                        family: 'Inter'
                    }
                }
            },
            x: {
                ticks: {
                    font: {
                        family: 'Inter'
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endsection
