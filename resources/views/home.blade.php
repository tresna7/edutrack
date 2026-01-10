@extends('layouts.layout')

@section('title', 'Admin Dashboard - EduTrack')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card-modern bg-purple">
                <div class="stat-content">
                    <div class="stat-label">Students</div>
                    <div class="stat-value">{{ number_format($totalStudents ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern bg-blue">
                <div class="stat-content">
                    <div class="stat-label">Teachers</div>
                    <div class="stat-value">12</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern bg-orange">
                <div class="stat-content">
                    <div class="stat-label">Classes</div>
                    <div class="stat-value">9</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-grid-3x3"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern bg-green">
                <div class="stat-content">
                    <div class="stat-label">Subjects</div>
                    <div class="stat-value">{{ \App\Models\Subject::count() }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-book"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Info Row -->
    <div class="row g-3 mb-4">
        <!-- Exam Results Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Student Exam Results</h5>
                        <small class="text-muted">Monthly Performance</small>
                    </div>
                    <div class="chart-legend">
                        <span class="legend-item"><span class="dot bg-purple"></span> High Score</span>
                        <span class="legend-item"><span class="dot bg-orange"></span> Average Score</span>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="examChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Students Donut Chart -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Students</h5>
                    <i class="bi bi-three-dots"></i>
                </div>
                <div class="card-body text-center">
                    <div style="position: relative; height: 250px; width: 100%;">
                        <canvas id="studentsChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-center gap-4">
                            <div>
                                <span class="dot bg-purple"></span>
                                <span class="text-muted">Male</span>
                            </div>
                            <div>
                                <span class="dot bg-orange"></span>
                                <span class="text-muted">Female</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row g-3">
        <!-- Star Students -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Star Students</h5>
                    <i class="bi bi-three-dots"></i>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50"></th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Marks</th>
                                <th>Percent</th>
                                <th>Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topStudents ?? [] as $student)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random" 
                                             class="rounded-circle" width="32" height="32" alt="{{ $student->name }}">
                                        <span>{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $student->nis }}</td>
                                <td>{{ number_format($student->average_score, 0) }}</td>
                                <td>{{ number_format(($student->average_score / 100) * 100, 0) }}%</td>
                                <td>2024</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Exam Results Timeline -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Exam Results</h5>
                    <i class="bi bi-three-dots"></i>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon bg-blue">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>New Teacher</h6>
                                <p class="text-muted small">It is a long established readable...</p>
                                <small class="text-muted">Just now</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon bg-pink">
                                <i class="bi bi-cash"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Fees Structure</h6>
                                <p class="text-muted small">It is a long established readable...</p>
                                <small class="text-muted">Today</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon bg-teal">
                                <i class="bi bi-book"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>New Course</h6>
                                <p class="text-muted small">It is a long established readable...</p>
                                <small class="text-muted">24 Sep 2023</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    padding: 0;
}

.stat-card-modern {
    border-radius: 16px;
    padding: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    transition: transform 0.3s;
}

.stat-card-modern:hover {
    transform: translateY(-4px);
}

.stat-card-modern.bg-purple {
    background: linear-gradient(135deg, #9D7CE8 0%, #B799FF 100%);
}

.stat-card-modern.bg-blue {
    background: linear-gradient(135deg, #4FC3F7 0%, #29B6F6 100%);
}

.stat-card-modern.bg-orange {
    background: linear-gradient(135deg, #FFB74D 0%, #FFA726 100%);
}

.stat-card-modern.bg-green {
    background: linear-gradient(135deg, #81C784 0%, #66BB6A 100%);
}

.stat-label {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
}

.stat-icon {
    font-size: 48px;
    opacity: 0.3;
}

.chart-legend {
    display: flex;
    gap: 20px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

.dot.bg-purple {
    background: #9D7CE8;
}

.dot.bg-orange {
    background: #FFB74D;
}

.timeline {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.timeline-item {
    display: flex;
    gap: 12px;
}

.timeline-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.timeline-icon.bg-blue {
    background: #4FC3F7;
}

.timeline-icon.bg-pink {
    background: #F06292;
}

.timeline-icon.bg-teal {
    background: #4DB6AC;
}

.timeline-content h6 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
}

.timeline-content p {
    margin: 0 0 4px 0;
}
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Exam Results Chart
const examCtx = document.getElementById('examChart');
if (examCtx) {
    new Chart(examCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'High Score',
                data: [85, 88, 82, 90, 95, 87, 89, 84, 91, 93, 88, 86],
                backgroundColor: '#9D7CE8',
                borderRadius: 4
            }, {
                label: 'Average Score',
                data: [70, 72, 68, 75, 78, 73, 74, 71, 76, 77, 74, 72],
                backgroundColor: '#FFB74D',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

// Students Donut Chart
const studentsCtx = document.getElementById('studentsChart');
if (studentsCtx) {
    new Chart(studentsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [60, 40],
                backgroundColor: ['#9D7CE8', '#FFB74D'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}
</script>
@endsection
