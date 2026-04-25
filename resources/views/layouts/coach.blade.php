<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Coach Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background-color: #f8f9fc;
        }
        
        .topbar {
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 1rem 1.5rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
        .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
        .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
        .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
        .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="p-3">
            <h4 class="text-white mb-0">
                <i class="fas fa-swimming-pool me-2"></i>
                Coach Panel
            </h4>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}" 
                   href="{{ route('coach.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="nav-header text-white-50 px-3 py-2 mb-0">KELAS SAYA</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('coach.kelas.*') ? 'active' : '' }}" 
                   href="{{ route('coach.kelas.index') }}">
                    <i class="fas fa-chalkboard"></i>
                    Kelas
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('coach.siswa.*') ? 'active' : '' }}" 
                   href="{{ route('coach.siswa.index') }}">
                    <i class="fas fa-users"></i>
                    Siswa
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="nav-header text-white-50 px-3 py-2 mb-0">LATIHAN</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('coach.sesi.*') ? 'active' : '' }}" 
                   href="{{ route('coach.sesi.index') }}">
                    <i class="fas fa-calendar-alt"></i>
                    Sesi Latihan
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('coach.kehadiran.*') ? 'active' : '' }}" 
                   href="{{ route('coach.kehadiran.index') }}">
                    <i class="fas fa-check-circle"></i>
                    Kehadiran
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('coach.catatan-latihan.*') ? 'active' : '' }}" 
                   href="{{ route('coach.catatan-latihan.index') }}">
                    <i class="fas fa-clipboard-list"></i>
                    Catatan Latihan
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="nav-header text-white-50 px-3 py-2 mb-0">PRESTASI</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('coach.catatan-waktu.*') ? 'active' : '' }}" 
                   href="{{ route('coach.catatan-waktu.index') }}">
                    <i class="fas fa-stopwatch"></i>
                    Catatan Waktu
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('coach.personal-best.*') ? 'active' : '' }}" 
                   href="{{ route('coach.personal-best.index') }}">
                    <i class="fas fa-medal"></i>
                    Personal Best
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('coach.rapor.*') ? 'active' : '' }}" 
                   href="{{ route('coach.rapor.index') }}">
                    <i class="fas fa-file-alt"></i>
                    Rapor
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="nav-header text-white-50 px-3 py-2 mb-0">LAPORAN</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('coach.laporan.*') ? 'active' : '' }}" 
                   href="{{ route('coach.laporan.index') }}">
                    <i class="fas fa-chart-bar"></i>
                    Laporan Kelas
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <nav class="topbar d-flex justify-content-between align-items-center">
            <button class="btn btn-link d-md-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg me-2"></i>
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i> Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid p-4">
            <x-alert />
            
            {{ $slot }}
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    
    @stack('scripts')
</body>
</html>