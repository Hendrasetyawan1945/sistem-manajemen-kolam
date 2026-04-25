<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Portal Siswa</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #1cc88a 0%, #13855c 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white !important;
        }
        
        .navbar-custom .nav-link:hover {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .navbar-custom .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.375rem;
        }
        
        .main-content {
            min-height: calc(100vh - 76px);
            background-color: #f8f9fc;
            padding-top: 2rem;
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
        
        .hero-section {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        
        .info-card {
            transition: transform 0.2s;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
        }
    </style>
    
    @stack('styles')
</head>

<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('siswa.dashboard') }}">
                <i class="fas fa-swimming-pool me-2"></i>
                Portal Siswa
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}" 
                           href="{{ route('siswa.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.kehadiran.*') ? 'active' : '' }}" 
                           href="{{ route('siswa.kehadiran.index') }}">
                            <i class="fas fa-check-circle me-1"></i>
                            Kehadiran
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.keuangan.*') ? 'active' : '' }}" 
                           href="{{ route('siswa.keuangan.index') }}">
                            <i class="fas fa-money-bill-wave me-1"></i>
                            Keuangan
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.prestasi.*') ? 'active' : '' }}" 
                           href="{{ route('siswa.prestasi.index') }}">
                            <i class="fas fa-medal me-1"></i>
                            Prestasi
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.rapor.*') ? 'active' : '' }}" 
                           href="{{ route('siswa.rapor.index') }}">
                            <i class="fas fa-file-alt me-1"></i>
                            Rapor
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.jersey.*') ? 'active' : '' }}" 
                           href="{{ route('siswa.jersey.index') }}">
                            <i class="fas fa-tshirt me-1"></i>
                            Jersey
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
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
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <x-alert />
            
            {{ $slot }}
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        &copy; {{ date('Y') }} Sistem Manajemen Klub Renang. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        <i class="fas fa-swimming-pool me-1"></i>
                        Portal Siswa v1.0
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>