@extends('layouts.layout')

@section('title', 'Tambah Nilai - EduTrack')

@section('content')
<div class="row justify-content-center fade-in-up">
    <div class="col-md-7">
        <div class="card shadow-soft">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-plus-circle-fill me-2 text-gradient-primary"></i>Tambah Nilai Siswa
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('grades.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                        <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($students as $s)
                                <option value="{{ $s->id }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->nis }}) - {{ $s->class }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}" {{ old('subject_id') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->subject_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Belum ada mata pelajaran? <a href="{{ route('subjects.create') }}" target="_blank">Tambah di sini</a>
                        </small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                <option value="">-- Pilih Semester --</option>
                                <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                                <option value="3" {{ old('semester') == '3' ? 'selected' : '' }}>Semester 3 (Ganjil)</option>
                                <option value="4" {{ old('semester') == '4' ? 'selected' : '' }}>Semester 4 (Genap)</option>
                                <option value="5" {{ old('semester') == '5' ? 'selected' : '' }}>Semester 5 (Ganjil)</option>
                                <option value="6" {{ old('semester') == '6' ? 'selected' : '' }}>Semester 6 (Genap)</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                            <input type="number" name="score" class="form-control @error('score') is-invalid @enderror" 
                                   min="0" max="100" value="{{ old('score') }}" placeholder="Masukkan nilai" required>
                            @error('score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-lightbulb-fill me-2"></i>
                        <strong>Info:</strong> Status risiko siswa akan otomatis ter-update berdasarkan rata-rata nilai.
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save me-1"></i> Simpan Nilai
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection