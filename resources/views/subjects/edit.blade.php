@extends('layouts.layout')

@section('title', 'Edit Mata Pelajaran - EduTrack')

@section('content')
<div class="row justify-content-center fade-in-up">
    <div class="col-md-6">
        <div class="card shadow-soft">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square me-2 text-gradient-primary"></i>Edit Mata Pelajaran
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" name="subject_name" class="form-control @error('subject_name') is-invalid @enderror" 
                               value="{{ old('subject_name', $subject->subject_name) }}" placeholder="Contoh: Matematika, Bahasa Indonesia" required>
                        @error('subject_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
