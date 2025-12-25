<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Nilai Baru - EduTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tambah Nilai Siswa</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('grades.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Pilih Siswa</label>
                                <select name="student_id" class="form-select" required>
                                    @foreach($students as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mata Pelajaran</label>
                                <select name="subject_id" class="form-select" required>
                                    @foreach($subjects as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->subject_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Semester</label>
                                    <input type="number" name="semester" class="form-control" placeholder="1 atau 2" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nilai (0-100)</label>
                                    <input type="number" name="score" class="form-control" required>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Simpan Nilai</button>
                                <a href="/students" class="btn btn-light">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>