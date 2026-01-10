@extends('layouts.layout')

@section('title', 'Daftar Nilai - EduTrack')

@section('content')
<div class="fade-in-up">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-clipboard-data me-2"></i>Manajemen Nilai</h4>
            <p class="text-muted mb-0">Kelola nilai siswa untuk semua mata pelajaran</p>
        </div>
        <a href="{{ route('grades.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Nilai
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow-soft mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('grades.index') }}" class="row g-3">
                <!-- Search Box -->
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Cari nama siswa atau mata pelajaran..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                
                <!-- Semester Filter -->
                <div class="col-md-3">
                    <select name="semester" class="form-select">
                        <option value="">Semua Semester</option>
                        <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                        <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('grades.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
            
            <!-- Results Counter -->
            <div class="mt-3 text-muted small">
                <i class="bi bi-info-circle me-1"></i>
                Menampilkan <strong>{{ $grades->count() }}</strong> dari <strong>{{ $grades->total() }}</strong> nilai
            </div>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="card shadow-soft">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="60">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $index => $grade)
                        <tr>
                            <td class="text-center">{{ $grades->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        {{ substr($grade->student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $grade->student->name }}</div>
                                        <small class="text-muted">NIS: {{ $grade->student->nis }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info">{{ $grade->student->class }}</span>
                            </td>
                            <td>{{ $grade->subject->subject_name }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary">Semester {{ $grade->semester }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $score = $grade->score;
                                    $badgeClass = $score >= 75 ? 'success' : ($score >= 60 ? 'warning' : 'danger');
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}-subtle text-{{ $badgeClass }} fs-6">
                                    {{ number_format($score, 0) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('grades.edit', $grade->id) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="confirmDelete({{ $grade->id }})"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">Belum ada data nilai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($grades->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $grades->firstItem() }} to {{ $grades->lastItem() }} of {{ $grades->total() }} entries
                </div>
                <div>
                    {{ $grades->links() }}
                </div>
            </div>
        </div>
        @endif
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
                Apakah Anda yakin ingin menghapus nilai ini? Data yang sudah dihapus tidak dapat dikembalikan.
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

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
    font-weight: 600;
}

/* Pagination Styling */
.pagination {
    margin: 0;
}

.pagination .page-link {
    color: var(--primary);
    border: 1px solid var(--gray-300);
    padding: 8px 12px;
    margin: 0 2px;
    border-radius: var(--radius);
    transition: all 0.2s;
}

.pagination .page-link:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pagination .page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: var(--gray-400);
    background: var(--gray-100);
    border-color: var(--gray-300);
}
</style>

<script>
function confirmDelete(gradeId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = '/grades/' + gradeId;
    modal.show();
}
</script>
@endsection
