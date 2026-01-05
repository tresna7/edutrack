@extends('layouts.layout')

@section('title', 'Daftar Mata Pelajaran - EduTrack')

@section('content')
<div class="fade-in-up">
    <div class="card shadow-soft">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-book me-2 text-gradient-primary"></i>Daftar Mata Pelajaran
            </h5>
            <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Mata Pelajaran
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">No</th>
                            <th>Nama Mata Pelajaran</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $index => $subject)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $subject->subject_name }}</td>
                            <td class="text-center">
                                <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-outline-primary btn-sm border-0" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button class="btn btn-outline-danger btn-sm border-0" onclick="confirmDelete({{ $subject->id }})" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada mata pelajaran. <a href="{{ route('subjects.create') }}">Tambah mata pelajaran baru</a>
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
                Apakah Anda yakin ingin menghapus mata pelajaran ini? Data yang sudah dihapus tidak dapat dikembalikan.
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
function confirmDelete(subjectId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = '/subjects/' + subjectId;
    modal.show();
}
</script>
@endsection
