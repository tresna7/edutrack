@extends('layouts.layout')

@section('title', 'Tambah Siswa Baru - EduTrack')

@section('content')
<div class="row justify-content-center fade-in-up">
    <div class="col-md-8">
        <div class="card shadow-soft">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-person-plus-fill me-2 text-gradient-primary"></i>Tambah Siswa Baru
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIS <span class="text-danger">*</span></label>
                            <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" 
                                   value="{{ old('nis') }}" placeholder="Masukkan NIS" required>
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
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
                                <option value="10-IPA-1" {{ old('class') == '10-IPA-1' ? 'selected' : '' }}>10-IPA-1</option>
                                <option value="10-IPA-2" {{ old('class') == '10-IPA-2' ? 'selected' : '' }}>10-IPA-2</option>
                                <option value="10-IPS-1" {{ old('class') == '10-IPS-1' ? 'selected' : '' }}>10-IPS-1</option>
                                <option value="11-IPA-1" {{ old('class') == '11-IPA-1' ? 'selected' : '' }}>11-IPA-1</option>
                                <option value="11-IPA-2" {{ old('class') == '11-IPA-2' ? 'selected' : '' }}>11-IPA-2</option>
                                <option value="11-IPS-1" {{ old('class') == '11-IPS-1' ? 'selected' : '' }}>11-IPS-1</option>
                                <option value="12-IPA-1" {{ old('class') == '12-IPA-1' ? 'selected' : '' }}>12-IPA-1</option>
                                <option value="12-IPA-2" {{ old('class') == '12-IPA-2' ? 'selected' : '' }}>12-IPA-2</option>
                                <option value="12-IPS-1" {{ old('class') == '12-IPS-1' ? 'selected' : '' }}>12-IPS-1</option>
                            </select>
                            @error('class')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="academic_year" class="form-control @error('academic_year') is-invalid @enderror" 
                                   value="{{ old('academic_year', '2024/2025') }}" placeholder="2024/2025">
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Data
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
