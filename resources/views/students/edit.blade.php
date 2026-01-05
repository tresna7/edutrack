@extends('layouts.layout')

@section('title', 'Edit Siswa - EduTrack')

@section('content')
<div class="row justify-content-center fade-in-up">
    <div class="col-md-8">
        <div class="card shadow-soft">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square me-2 text-gradient-primary"></i>Edit Data Siswa
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIS <span class="text-danger">*</span></label>
                            <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" 
                                   value="{{ old('nis', $student->nis) }}" placeholder="Masukkan NIS" required>
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $student->name) }}" placeholder="Masukkan nama lengkap" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select name="class" class="form-select @error('class') is-invalid @enderror" required>
                                <option value="">Pilih Kelas</option>
                                @foreach(['10-IPA-1', '10-IPA-2', '10-IPS-1', '11-IPA-1', '11-IPA-2', '11-IPS-1', '12-IPA-1', '12-IPA-2', '12-IPS-1'] as $class)
                                    <option value="{{ $class }}" {{ old('class', $student->class) == $class ? 'selected' : '' }}>{{ $class }}</option>
                                @endforeach
                            </select>
                            @error('class')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="academic_year" class="form-control @error('academic_year') is-invalid @enderror" 
                                   value="{{ old('academic_year', $student->academic_year) }}" placeholder="2024/2025">
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update Data
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
