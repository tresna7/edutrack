<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduTrack')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @yield('styles')
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="sidebar-logo">
                <i class="bi bi-mortarboard-fill"></i>
                <span>EduTrack</span>
            </a>
            
            <!-- Main Navigation -->
            <nav class="sidebar-nav">
                <!-- Home -->
                <a href="{{ route('home') }}" class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Home</span>
                </a>
                
                <!-- Academic Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Academic</div>
                </div>
                
                <!-- Students Dropdown -->
                <div class="sidebar-dropdown">
                    <a href="#" class="sidebar-item {{ request()->routeIs('students.*') ? 'active' : '' }}" onclick="toggleDropdown(event, 'students-menu')">
                        <i class="bi bi-people"></i>
                        <span>Students</span>
                        <i class="bi bi-chevron-down ms-auto dropdown-arrow"></i>
                    </a>
                    <div class="sidebar-submenu" id="students-menu">
                        <a href="{{ route('students.index') }}" class="sidebar-subitem {{ request()->routeIs('students.index') ? 'active' : '' }}">
                            <span>All Students</span>
                        </a>
                        <a href="{{ route('students.create') }}" class="sidebar-subitem {{ request()->routeIs('students.create') ? 'active' : '' }}">
                            <span>Add Student</span>
                        </a>
                    </div>
                </div>
                
                <!-- Subjects -->
                <a href="{{ route('subjects.index') }}" class="sidebar-item {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                    <i class="bi bi-book"></i>
                    <span>Subjects</span>
                </a>
                
                <!-- Grades Dropdown -->
                <div class="sidebar-dropdown">
                    <a href="#" class="sidebar-item {{ request()->routeIs('grades.*') ? 'active' : '' }}" onclick="toggleDropdown(event, 'grades-menu')">
                        <i class="bi bi-clipboard-data"></i>
                        <span>Grades</span>
                        <i class="bi bi-chevron-down ms-auto dropdown-arrow"></i>
                    </a>
                    <div class="sidebar-submenu" id="grades-menu">
                        <a href="{{ route('grades.index') }}" class="sidebar-subitem {{ request()->routeIs('grades.index') ? 'active' : '' }}">
                            <span>All Grades</span>
                        </a>
                        <a href="{{ route('grades.create') }}" class="sidebar-subitem {{ request()->routeIs('grades.create') ? 'active' : '' }}">
                            <span>Add Grade</span>
                        </a>
                    </div>
                </div>
                
                <!-- Attendance Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Attendance</div>
                </div>
                
                <!-- Attendance Dropdown -->
                <div class="sidebar-dropdown">
                    <a href="#" class="sidebar-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}" onclick="toggleDropdown(event, 'attendance-menu')">
                        <i class="bi bi-calendar-check"></i>
                        <span>Attendance</span>
                        <i class="bi bi-chevron-down ms-auto dropdown-arrow"></i>
                    </a>
                    <div class="sidebar-submenu" id="attendance-menu">
                        <a href="{{ route('attendance.index') }}" class="sidebar-subitem {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('attendance.create') }}" class="sidebar-subitem {{ request()->routeIs('attendance.create') ? 'active' : '' }}">
                            <span>Manual Input</span>
                        </a>
                        <a href="{{ route('attendance.qr.generate') }}" class="sidebar-subitem {{ request()->routeIs('attendance.qr.generate') ? 'active' : '' }}">
                            <span>Generate QR</span>
                        </a>
                        <a href="{{ route('attendance.qr.scan') }}" class="sidebar-subitem {{ request()->routeIs('attendance.qr.scan') ? 'active' : '' }}">
                            <span>Scan QR</span>
                        </a>
                    </div>
                </div>
                
                <!-- Reports Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Reports</div>
                </div>
                
                <!-- Risk Analysis -->
                <a href="{{ route('risk.analysis') }}" class="sidebar-item {{ request()->routeIs('risk.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Risk Analysis</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <nav class="navbar">
                <div class="d-flex align-items-center gap-3">
                    <h1 class="navbar-brand mb-0">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Search -->
                    <div class="search-box d-none d-md-block">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search..." class="search-input">
                    </div>
                    
                    <!-- Notifications -->
                    <a href="#" class="nav-icon">
                        <i class="bi bi-bell"></i>
                        <span class="badge-dot"></span>
                    </a>
                    
                    <!-- User Profile -->
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=9D7CE8&color=fff" alt="User" class="user-avatar">
                        <div class="d-none d-md-block ms-2">
                            <div class="user-name">Admin</div>
                            <div class="user-role">Administrator</div>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Page Content -->
            <main class="content-wrapper">
                <div class="container-fluid py-4">
                    <!-- Session Messages -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0 text-muted">&copy; 2025 EduTrack. All rights reserved.</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0 text-muted">Made with <i class="bi bi-heart-fill text-danger"></i> for Education</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container"></div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toast Notification Script -->
    <script>
        function showToast(message, type = 'success') {
            const container = document.querySelector('.toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icon = type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill';
            const iconColor = type === 'success' ? 'text-success' : 'text-danger';
            
            toast.innerHTML = `
                <i class="bi bi-${icon} ${iconColor}"></i>
                <span>${message}</span>
            `;
            
            container.appendChild(toast);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Dropdown toggle function
        function toggleDropdown(event, menuId) {
            event.preventDefault();
            const dropdown = event.currentTarget.closest('.sidebar-dropdown');
            const allDropdowns = document.querySelectorAll('.sidebar-dropdown');
            
            // Close other dropdowns
            allDropdowns.forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('open');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('open');
        }
    </script>
    
    @yield('scripts')
</body>
</html>
