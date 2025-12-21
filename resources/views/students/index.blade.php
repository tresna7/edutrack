<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTrack - Dashboard Monitoring Siswa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 800; letter-spacing: 1px; }
        .card { border: none; border-radius: 12px; }
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-mortarboard-fill text-primary"></i> EDUTRACK
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/students"><i class="bi bi-people"></i> Daftar Siswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-book"></i> Mata Pelajaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="#"><i class="bi bi-graph-up-arrow"></i> Analisis Risiko</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stat-card bg-primary text-white shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="fs-1 me-3"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <h6 class="mb-0">Total Siswa</h6>
                            <h2 class="mb-0 fw-bold">{{ $students->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card bg-success text-white shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="fs-1 me-3"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <h6 class="mb-0">Kondisi Aman</h6>
                            <h2 class="mb-0 fw-bold">{{ $students->where('risk_level', 'Aman')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card bg-danger text-white shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="fs-1 me-3"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <div>
                            <h6 class="mb-0">Berisiko Tinggi</h6>
                            <h2 class="mb-0 fw-bold">{{ $students->where('risk_level', 'Risiko')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-table me-2 text-primary"></i>Data Monitoring Siswa
                </h5>
                <a href="#" class="btn btn-primary btn-sm rounded-pill shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Siswa
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">NIS</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
                                <th>Tahun Akademik</th>
                                <th class="text-center">Status Risiko</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $s)
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">{{ $s->nis }}</td>
                                <td>
                                    <div class="fw-bold">{{ $s->name }}</div>
                                    <small class="text-muted">Siswa Aktif</small>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $s->class }}</span></td>
                                <td>{{ $s->academic_year }}</td>
                                <td class="text-center">
                                    @if($s->risk_level == 'Aman')
                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success px-3">
                                            <i class="bi bi-check-circle-fill me-1"></i> Aman
                                        </span>
                                    @elseif($s->risk_level == 'Waspada')
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning px-3">
                                            <i class="bi bi-info-circle-fill me-1"></i> Waspada
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger px-3">
                                            <i class="bi bi-exclamation-octagon-fill me-1"></i> Risiko
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-outline-secondary btn-sm border-0"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-outline-primary btn-sm border-0"><i class="bi bi-pencil-square"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-center py-3">
                <small class="text-muted">Sistem Deteksi Dini Putus Sekolah &copy; 2025 - PKM-KC EduTrack</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>