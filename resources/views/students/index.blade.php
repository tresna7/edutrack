@extends('layouts.layout')

@section('title', 'Daftar Siswa - EduTrack')

@section('content')
<div class="fade-in-up">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card bg-primary text-white shadow-soft">
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
            <div class="card stat-card bg-success text-white shadow-soft">
                <div class="card-body d-flex align-items-center">
                    <div class="fs-1 me-3"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <h6 class="mb-0">Kondisi Aman</h6>
                        <h2 class="mb-0 fw-bold">{{ $students->filter(fn($s) => $s->automatic_risk_level == 'Aman')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card bg-danger text-white shadow-soft">
                <div class="card-body d-flex align-items-center">
                    <div class="fs-1 me-3"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div>
                        <h6 class="mb-0">Berisiko Tinggi</h6>
                        <h2 class="mb-0 fw-bold">{{ $students->filter(fn($s) => $s->automatic_risk_level == 'Risiko Tinggi')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card shadow-soft">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bi bi-table me-2 text-gradient-primary"></i>Data Monitoring Siswa
            </h5>
            <div>
                <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm me-2">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Siswa
                </a>
                <a href="{{ route('grades.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Nilai
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">NIS</th>
                            <th>Nama Lengkap</th>
                            <th>Kelas</th>
                            <th>Semester</th>
                            <th class="text-center">Status Risiko (Avg)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $s)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">{{ $s->nis }}</td>
                            <td>
                                <div class="fw-bold">{{ $s->name }}</div>
                                <small class="text-muted">Siswa Aktif</small>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $s->class }}</span></td>
                            <td>
                                @if($s->grades->isNotEmpty())
                                    <span class="fw-bold text-dark">{{ $s->grades->last()->semester }}</span>
                                    <small class="text-muted">({{ $s->grades->last()->semester % 2 == 0 ? 'Genap' : 'Ganjil' }})</small>
                                @else
                                    <span class="text-muted small">No Data</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $status = $s->automatic_risk_level;
                                    $average = $s->average_score;
                                @endphp

                                @if($status == 'Aman')
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success px-3">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aman ({{ number_format($average, 1) }})
                                    </span>
                                @elseif($status == 'Waspada')
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning px-3">
                                        <i class="bi bi-info-circle-fill me-1"></i> Waspada ({{ number_format($average, 1) }})
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger px-3">
                                        <i class="bi bi-exclamation-octagon-fill me-1"></i> {{ $status }} ({{ number_format($average, 1) }})
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('students.show', $s->id) }}" class="btn btn-outline-info btn-sm border-0" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $s->id) }}" class="btn btn-outline-primary btn-sm border-0" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button class="btn btn-outline-danger btn-sm border-0" onclick="confirmDelete({{ $s->id }})" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data siswa. <a href="{{ route('students.create') }}">Tambah siswa baru</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus siswa ini? Data yang sudah dihapus tidak dapat dikembalikan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
function confirmDelete(studentId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = '/students/' + studentId;
    modal.show();
}
</script>
@endsection