@extends('layouts.layout')

@section('title', 'Generate QR Code - EduTrack')

@section('content')
<div class="fade-in-up">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-soft">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h5 class="mb-0"><i class="bi bi-qr-code me-2"></i>QR Code Absensi Hari Ini</h5>
                    <small>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- QR Code Display -->
                        <div class="col-md-6 text-center">
                            <div id="qrcode" class="mb-3 d-flex justify-content-center"></div>
                            
                            <!-- Valid Until -->
                            <div class="alert alert-info">
                                <i class="bi bi-clock me-2"></i>
                                Valid sampai: <strong id="validUntil"></strong>
                            </div>
                            
                            <!-- Refresh Button -->
                            <button onclick="location.reload()" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-clockwise me-1"></i> Refresh QR Code
                            </button>
                        </div>
                        
                        <!-- Instructions -->
                        <div class="col-md-6">
                            <div class="alert alert-warning mb-3">
                                <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Petunjuk untuk Siswa:</h6>
                                <ol class="mb-0 ps-3">
                                    <li class="mb-2">Buka halaman <strong>Scan QR</strong> di HP</li>
                                    <li class="mb-2">Izinkan akses <strong>Kamera</strong></li>
                                    <li class="mb-2">Izinkan akses <strong>Lokasi GPS</strong></li>
                                    <li class="mb-2">Pilih nama Anda dari dropdown</li>
                                    <li class="mb-2">Arahkan kamera ke QR code ini</li>
                                    <li>Tunggu konfirmasi absensi berhasil</li>
                                </ol>
                            </div>
                            
                            @if($school)
                            <div class="alert alert-secondary">
                                <h6 class="fw-bold"><i class="bi bi-geo-alt me-2"></i>Info Lokasi:</h6>
                                <p class="mb-1"><strong>Sekolah:</strong> {{ $school->name }}</p>
                                <p class="mb-1"><strong>Radius:</strong> {{ $school->attendance_radius }} meter</p>
                                <p class="mb-0 small text-muted">Pastikan Anda berada di area sekolah saat scan QR</p>
                            </div>
                            @endif
                            
                            <div class="alert alert-danger">
                                <h6 class="fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Perhatian:</h6>
                                <ul class="mb-0 ps-3">
                                    <li>QR code ini hanya valid selama <strong>30 menit</strong></li>
                                    <li>Tidak bisa menggunakan <strong>screenshot</strong></li>
                                    <li>Harus berada di <strong>area sekolah</strong></li>
                                    <li>Hanya bisa absen <strong>1 kali per hari</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                    </a>
                    <a href="{{ route('attendance.qr.scan') }}" class="btn btn-success">
                        <i class="bi bi-camera me-1"></i> Scan QR (Siswa)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
// Generate QR Code
const qrData = @json($qrString);
const qrcode = new QRCode(document.getElementById("qrcode"), {
    text: qrData,
    width: 300,
    height: 300,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

// Display valid until time
const validUntil = {{ $validUntil }};
const validDate = new Date(validUntil * 1000);
document.getElementById('validUntil').textContent = validDate.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
});

// Auto refresh when expired
const timeUntilExpiry = (validUntil - Math.floor(Date.now() / 1000)) * 1000;
if (timeUntilExpiry > 0) {
    setTimeout(() => {
        alert('QR Code sudah kadaluarsa. Halaman akan di-refresh.');
        location.reload();
    }, timeUntilExpiry);
}

// Update countdown every second
setInterval(() => {
    const now = Math.floor(Date.now() / 1000);
    const remaining = validUntil - now;
    
    if (remaining <= 0) {
        document.getElementById('validUntil').textContent = 'KADALUARSA';
        document.getElementById('validUntil').parentElement.className = 'alert alert-danger';
    }
}, 1000);
</script>
@endsection
