@extends('layouts.layout')

@section('title', 'Edit Nilai - EduTrack')

@section('content')
<div class="fade-in-up">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('grades.index') }}">Nilai</a></li>
                <li class="breadcrumb-item active">Edit Nilai</li>
            </ol>
        </nav>
        <h4 class="mb-1"><i class="bi bi-pencil-square me-2"></i>Edit Nilai</h4>
        <p class="text-muted mb-0">Ubah nilai siswa untuk mata pelajaran tertentu</p>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-soft">
                <div class="card-body">
                    <form action="{{ route('grades.update', $grade->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Student Selection -->
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $grade->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->nis }}) - {{ $student->class }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subject Selection -->
                        <div class="mb-3">
                            <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $grade->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->subject_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Semester Selection -->
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                <option value="">-- Pilih Semester --</option>
                                <option value="1" {{ old('semester', $grade->semester) == 1 ? 'selected' : '' }}>Semester 1</option>
                                <option value="2" {{ old('semester', $grade->semester) == 2 ? 'selected' : '' }}>Semester 2</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Score Input -->
                        <div class="mb-4">
                            <label for="score" class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="score" 
                                   id="score" 
                                   class="form-control @error('score') is-invalid @enderror" 
                                   min="0" 
                                   max="100" 
                                   step="0.01"
                                   value="{{ old('score', $grade->score) }}"
                                   required>
                            @error('score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Masukkan nilai antara 0 sampai 100</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Update Nilai
                            </button>
                            <a href="{{ route('grades.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-lg-4">
            <div class="card shadow-soft bg-info-subtle border-0">
                <div class="card-body">
                    <h6 class="card-title text-info"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
                    <ul class="mb-0 ps-3">
                        <li class="mb-2">Pastikan data siswa dan mata pelajaran sudah benar</li>
                        <li class="mb-2">Nilai harus antara 0-100</li>
                        <li class="mb-2">Pilih semester yang sesuai</li>
                        <li>Perubahan akan langsung tersimpan</li>
                    </ul>
                </div>
            </div>

            <!-- Current Grade Info -->
            <div class="card shadow-soft mt-3">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-clipboard-data me-2"></i>Data Saat Ini</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Siswa:</td>
                            <td class="fw-semibold">{{ $grade->student->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kelas:</td>
                            <td><span class="badge bg-info-subtle text-info">{{ $grade->student->class }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Mapel:</td>
                            <td class="fw-semibold">{{ $grade->subject->subject_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Semester:</td>
                            <td><span class="badge bg-secondary-subtle text-secondary">{{ $grade->semester }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nilai:</td>
                            <td>
                                @php
                                    $score = $grade->score;
                                    $badgeClass = $score >= 75 ? 'success' : ($score >= 60 ? 'warning' : 'danger');
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}-subtle text-{{ $badgeClass }} fs-6">
                                    {{ number_format($score, 0) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
