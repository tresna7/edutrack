@extends('layouts.layout')

@section('title', 'Detail Siswa - ' . $student->name)

@section('content')
<div class="fade-in-up">
    <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-dark mb-4">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-soft border-0">
                <div class="card-body text-center p-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 100px; height: 100px;">
                        <i class="bi bi-person-fill fs-1"></i>
                    </div>
                    <h4 class="fw-bold">{{ $student->name }}</h4>
                    <p class="text-muted mb-1">NIS: {{ $student->nis }}</p>
                    <p class="badge bg-info text-dark mb-3">Kelas: {{ $student->class }}</p>
                    
                    <hr>
                    
                    @php
                        $status = $student->automatic_risk_level;
                        $average = $student->average_score;
                    @endphp
                    
                    <div class="alert {{ $status == 'Aman' ? 'alert-success' : ($status == 'Waspada' ? 'alert-warning' : 'alert-danger') }} py-2 mb-3">
                        <strong>Status: {{ $status }}</strong><br>
                        <small>Rata-rata: {{ number_format($average, 1) }}</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-1"></i> Edit Data
                        </a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $student->id }})">
                            <i class="bi bi-trash me-1"></i> Hapus Siswa
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow-soft border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-journal-text me-2 text-gradient-primary"></i>Riwayat Nilai
                    </h5>
                    <a href="{{ route('grades.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Nilai
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Mata Pelajaran</th>
                                    <th>Semester</th>
                                    <th class="text-center">Nilai</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->grades as $g)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $g->subject->subject_name }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $g->semester }}</span> 
                                        <small class="text-muted">({{ $g->semester % 2 == 0 ? 'Genap' : 'Ganjil' }})</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $g->score < 60 ? 'bg-danger' : ($g->score < 75 ? 'bg-warning' : 'bg-success') }} fs-6">
                                            {{ $g->score }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($g->score < 60)
                                            <span class="badge bg-danger-subtle text-danger border border-danger">Kurang</span>
                                        @elseif($g->score < 75)
                                            <span class="badge bg-warning-subtle text-warning border border-warning">Cukup</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success">Baik</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada data nilai untuk siswa ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
                Apakah Anda yakin ingin menghapus siswa <strong>{{ $student->name }}</strong>? 
                Semua data nilai siswa ini juga akan terhapus dan tidak dapat dikembalikan.
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