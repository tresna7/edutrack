<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Siswa - {{ $student->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5 pb-5">
        <a href="{{ url('/students') }}" class="btn btn-sm btn-outline-dark mb-4"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill fs-1"></i>
                        </div>
                        <h4 class="fw-bold">{{ $student->name }}</h4>
                        <p class="text-muted mb-0">NIS: {{ $student->nis }}</p>
                        <p class="badge bg-info text-dark">Kelas: {{ $student->class }}</p>
                        <hr>
                        <div class="alert {{ $student->risk_level == 'Aman' ? 'alert-success' : 'alert-danger' }} py-2">
                            Status: <strong>{{ $student->risk_level }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2"></i>Riwayat Nilai</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Mata Pelajaran</th>
                                    <th>Semester</th>
                                    <th class="text-center">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->grades as $g)
                                <tr>
                                    <td class="ps-4">{{ $g->subject->subject_name }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $g->semester }}</span> 
                                        <small class="text-muted">({{ $g->semester % 2 == 0 ? 'Genap' : 'Ganjil' }})</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold {{ $g->score < 60 ? 'text-danger' : 'text-success' }}">
                                            {{ $g->score }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada data nilai.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>