@extends('layouts.layout')

@section('title', 'Scan QR Code - EduTrack')

@section('content')
<div class="fade-in-up">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-soft">
                <div class="card-header bg-success text-white text-center py-3">
                    <h5 class="mb-0"><i class="bi bi-camera me-2"></i>Scan QR Code Absensi</h5>
                    <small>Pastikan kamera dan GPS aktif</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Scanner Section -->
                        <div class="col-md-7">
                            <!-- Student Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Nama Anda <span class="text-danger">*</span></label>
                                <select id="studentSelect" class="form-select form-select-lg">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->class }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- QR Scanner -->
                            <div id="reader" class="mb-3 border rounded"></div>
                            
                            <!-- Status Messages -->
                            <div id="statusMessage"></div>
                        </div>
                        
                        <!-- Info Section -->
                        <div class="col-md-5">
                            <!-- GPS Status -->
                            <div id="gpsStatus" class="alert alert-secondary">
                                <h6 class="fw-bold"><i class="bi bi-geo-alt me-2"></i>Status GPS</h6>
                                <p class="mb-0">Menunggu lokasi GPS...</p>
                            </div>
                            
                            <!-- Instructions -->
                            <div class="alert alert-info">
                                <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Cara Scan:</h6>
                                <ol class="mb-0 ps-3 small">
                                    <li class="mb-1">Pilih nama Anda dari dropdown</li>
                                    <li class="mb-1">Izinkan akses kamera saat diminta</li>
                                    <li class="mb-1">Izinkan akses lokasi GPS</li>
                                    <li class="mb-1">Arahkan kamera ke QR code</li>
                                    <li>Tunggu konfirmasi berhasil</li>
                                </ol>
                            </div>
                            
                            <!-- Requirements -->
                            <div class="alert alert-warning">
                                <h6 class="fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Syarat:</h6>
                                <ul class="mb-0 ps-3 small">
                                    <li>Berada di area sekolah</li>
                                    <li>QR code masih valid</li>
                                    <li>Belum absen hari ini</li>
                                    <li>GPS aktif dan akurat</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <a href="{{ route('attendance.qr.generate') }}" class="btn btn-primary">
                        <i class="bi bi-qr-code me-1"></i> Lihat QR Code
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let userLatitude = null;
let userLongitude = null;
let scannerStarted = false;

// Request GPS location
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        (position) => {
            userLatitude = position.coords.latitude;
            userLongitude = position.coords.longitude;
            document.getElementById('gpsStatus').innerHTML = `
                <h6 class="fw-bold"><i class="bi bi-check-circle me-2"></i>Status GPS</h6>
                <p class="mb-1"><strong>Latitude:</strong> ${userLatitude.toFixed(6)}</p>
                <p class="mb-0"><strong>Longitude:</strong> ${userLongitude.toFixed(6)}</p>
            `;
            document.getElementById('gpsStatus').className = 'alert alert-success';
        },
        (error) => {
            let errorMsg = 'Gagal mendapatkan lokasi GPS.';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMsg = 'Akses lokasi ditolak. Izinkan akses lokasi di browser!';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMsg = 'Informasi lokasi tidak tersedia.';
                    break;
                case error.TIMEOUT:
                    errorMsg = 'Request lokasi timeout.';
                    break;
            }
            document.getElementById('gpsStatus').innerHTML = `
                <h6 class="fw-bold"><i class="bi bi-x-circle me-2"></i>Status GPS</h6>
                <p class="mb-0">${errorMsg}</p>
            `;
            document.getElementById('gpsStatus').className = 'alert alert-danger';
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
} else {
    document.getElementById('gpsStatus').innerHTML = `
        <h6 class="fw-bold"><i class="bi bi-x-circle me-2"></i>Status GPS</h6>
        <p class="mb-0">Browser tidak mendukung GPS!</p>
    `;
    document.getElementById('gpsStatus').className = 'alert alert-danger';
}

// Initialize QR Scanner
const html5QrCode = new Html5Qrcode("reader");

// Start scanner
function startScanner() {
    if (scannerStarted) return;
    
    html5QrCode.start(
        { facingMode: "environment" },
        { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        },
        onScanSuccess,
        onScanFailure
    ).then(() => {
        scannerStarted = true;
    }).catch(err => {
        showMessage('Gagal mengakses kamera: ' + err, 'danger');
    });
}

// Start scanner automatically
setTimeout(startScanner, 500);

function onScanSuccess(decodedText, decodedResult) {
    const studentId = document.getElementById('studentSelect').value;
    
    if (!studentId) {
        showMessage('Pilih nama Anda terlebih dahulu!', 'warning');
        return;
    }
    
    if (!userLatitude || !userLongitude) {
        showMessage('Lokasi GPS belum terdeteksi! Pastikan GPS aktif.', 'danger');
        return;
    }
    
    // Stop scanner
    html5QrCode.stop().then(() => {
        scannerStarted = false;
        showMessage('Memproses absensi...', 'info');
        
        // Send check-in request
        fetch('{{ route("attendance.checkin") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                qr_data: decodedText,
                student_id: studentId,
                latitude: userLatitude,
                longitude: userLongitude
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("attendance.index") }}';
                }, 2000);
            } else {
                showMessage(data.message, 'danger');
                // Restart scanner after 3 seconds
                setTimeout(() => {
                    startScanner();
                }, 3000);
            }
        })
        .catch(error => {
            showMessage('Terjadi kesalahan: ' + error.message, 'danger');
            setTimeout(() => {
                startScanner();
            }, 3000);
        });
    });
}

function onScanFailure(error) {
    // Silent fail - normal when no QR detected
}

function showMessage(message, type) {
    const statusDiv = document.getElementById('statusMessage');
    const iconMap = {
        'success': 'check-circle',
        'danger': 'x-circle',
        'warning': 'exclamation-triangle',
        'info': 'info-circle'
    };
    const icon = iconMap[type] || 'info-circle';
    
    statusDiv.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show">
            <i class="bi bi-${icon} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}
</script>

<style>
#reader {
    min-height: 300px;
}

#reader video {
    width: 100%;
    border-radius: 8px;
}
</style>
@endsection
