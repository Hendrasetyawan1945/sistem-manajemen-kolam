<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Klub Renang - Admin Panel</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #c0392b 0%, #96281b 100%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        /* Brand header - fixed */
        .sidebar-brand {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }

        /* Scrollable nav area */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 16px;
        }

        /* Custom scrollbar */
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.4); }

        /* Section headers */
        .nav-section {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.45);
            padding: 14px 20px 4px;
            text-transform: uppercase;
        }

        /* Nav links */
        .sidebar .nav-link {
            color: rgba(255,255,255,0.82);
            padding: 9px 20px;
            font-size: 13.5px;
            border-radius: 0;
            transition: background 0.2s, color 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar .nav-link i { width: 18px; text-align: center; font-size: 13px; }
        .sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,0.12); }
        .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.18); font-weight: 600; }

        /* Sub-menu toggle */
        .sidebar .nav-link.submenu-toggle {
            cursor: pointer;
            justify-content: space-between;
        }
        .sidebar .nav-link.submenu-toggle .toggle-left { display: flex; align-items: center; gap: 10px; }
        .sidebar .nav-link.submenu-toggle .arrow {
            font-size: 11px;
            transition: transform 0.25s;
            color: rgba(255,255,255,0.5);
        }
        .sidebar .nav-link.submenu-toggle[aria-expanded="true"] .arrow { transform: rotate(90deg); }
        .sidebar .nav-link.submenu-toggle.active-parent { color: #fff; background: rgba(255,255,255,0.12); }

        /* Sub-menu items */
        .submenu { background: rgba(0,0,0,0.15); }
        .submenu .nav-link {
            padding: 7px 20px 7px 44px;
            font-size: 13px;
            color: rgba(255,255,255,0.72);
        }
        .submenu .nav-link:hover { color: #fff; background: rgba(255,255,255,0.1); }
        .submenu .nav-link.active { color: #fff; background: rgba(255,255,255,0.15); font-weight: 600; }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background-color: #f4f6f9;
        }

        .topbar {
            background: white;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .card { border: none; box-shadow: 0 0.1rem 1rem rgba(58,59,69,0.1); }

        .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
        .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
        .border-left-info    { border-left: 0.25rem solid #36b9cc !important; }
        .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
        .border-left-danger  { border-left: 0.25rem solid #e74a3b !important; }

        @media (max-width: 768px) {
            .sidebar { margin-left: -250px; }
            .sidebar.show { margin-left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<nav class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-water text-white fs-5"></i>
            <div>
                <div class="text-white fw-bold" style="font-size:15px; line-height:1.2">Admin Panel</div>
                <div style="font-size:11px; color:rgba(255,255,255,0.55)">Klub Renang</div>
            </div>
        </div>
    </div>

    {{-- Scrollable nav --}}
    <div class="sidebar-nav">
        <ul class="nav flex-column mt-1">

            {{-- Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>

            {{-- ── MANAJEMEN DATA ── --}}
            <li><div class="nav-section">Manajemen Data</div></li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}"
                   href="{{ route('admin.siswa.index') }}">
                    <i class="fas fa-users"></i> Siswa
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}"
                   href="{{ route('admin.kelas.index') }}">
                    <i class="fas fa-chalkboard"></i> Kelas
                </a>
            </li>

            {{-- Sub-menu: Latihan & Kehadiran --}}
            @php
                $latihanActive = request()->routeIs('admin.sesi.*') || request()->routeIs('admin.kehadiran.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link submenu-toggle {{ $latihanActive ? 'active-parent' : '' }}"
                   data-bs-toggle="collapse" href="#sub-latihan"
                   aria-expanded="{{ $latihanActive ? 'true' : 'false' }}">
                    <span class="toggle-left">
                        <i class="fas fa-calendar-alt"></i> Latihan
                    </span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="collapse {{ $latihanActive ? 'show' : '' }}" id="sub-latihan">
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.sesi.*') ? 'active' : '' }}"
                               href="{{ route('admin.sesi.index') }}">
                                <i class="fas fa-calendar-day"></i> Sesi Latihan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.kehadiran.*') ? 'active' : '' }}"
                               href="{{ route('admin.kehadiran.index') }}">
                                <i class="fas fa-check-circle"></i> Rekap Kehadiran
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- ── KEUANGAN ── --}}
            <li><div class="nav-section">Keuangan</div></li>

            @php
                $keuanganActive = request()->routeIs('admin.iuran-rutin.*')
                    || request()->routeIs('admin.iuran-insidentil.*')
                    || request()->routeIs('admin.kejuaraan.*')
                    || request()->routeIs('admin.iuran-kejuaraan.*')
                    || request()->routeIs('admin.angsuran.*')
                    || request()->routeIs('admin.pengeluaran.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link submenu-toggle {{ $keuanganActive ? 'active-parent' : '' }}"
                   data-bs-toggle="collapse" href="#sub-keuangan"
                   aria-expanded="{{ $keuanganActive ? 'true' : 'false' }}">
                    <span class="toggle-left">
                        <i class="fas fa-wallet"></i> Iuran & Keuangan
                    </span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="collapse {{ $keuanganActive ? 'show' : '' }}" id="sub-keuangan">
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.iuran-rutin.*') ? 'active' : '' }}"
                               href="{{ route('admin.iuran-rutin.index') }}">
                                <i class="fas fa-money-bill-wave"></i> Iuran Rutin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.iuran-insidentil.*') ? 'active' : '' }}"
                               href="{{ route('admin.iuran-insidentil.index') }}">
                                <i class="fas fa-hand-holding-usd"></i> Iuran Insidentil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.kejuaraan.*') || request()->routeIs('admin.iuran-kejuaraan.*') ? 'active' : '' }}"
                               href="{{ route('admin.kejuaraan.index') }}">
                                <i class="fas fa-trophy"></i> Kejuaraan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.angsuran.*') ? 'active' : '' }}"
                               href="{{ route('admin.angsuran.index') }}">
                                <i class="fas fa-credit-card"></i> Angsuran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.pengeluaran.*') ? 'active' : '' }}"
                               href="{{ route('admin.pengeluaran.index') }}">
                                <i class="fas fa-receipt"></i> Pengeluaran
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- ── PRESTASI ── --}}
            <li><div class="nav-section">Prestasi</div></li>

            @php
                $prestasiActive = request()->routeIs('admin.catatan-waktu.*')
                    || request()->routeIs('admin.catatan-latihan.*')
                    || request()->routeIs('admin.personal-best.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link submenu-toggle {{ $prestasiActive ? 'active-parent' : '' }}"
                   data-bs-toggle="collapse" href="#sub-prestasi"
                   aria-expanded="{{ $prestasiActive ? 'true' : 'false' }}">
                    <span class="toggle-left">
                        <i class="fas fa-medal"></i> Catatan & Prestasi
                    </span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="collapse {{ $prestasiActive ? 'show' : '' }}" id="sub-prestasi">
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.catatan-waktu.*') ? 'active' : '' }}"
                               href="{{ route('admin.catatan-waktu.index') }}">
                                <i class="fas fa-stopwatch"></i> Catatan Waktu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.catatan-latihan.*') ? 'active' : '' }}"
                               href="{{ route('admin.catatan-latihan.index') }}">
                                <i class="fas fa-dumbbell"></i> Catatan Latihan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.personal-best.*') ? 'active' : '' }}"
                               href="{{ route('admin.personal-best.index') }}">
                                <i class="fas fa-star"></i> Personal Best
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.rapor.*') ? 'active' : '' }}"
                   href="{{ route('admin.rapor.index') }}">
                    <i class="fas fa-file-alt"></i> Rapor Siswa
                </a>
            </li>

            {{-- ── JERSEY ── --}}
            <li><div class="nav-section">Jersey</div></li>

            @php
                $jerseyActive = request()->routeIs('admin.jersey.*') || request()->routeIs('admin.master-ukuran-jersey.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link submenu-toggle {{ $jerseyActive ? 'active-parent' : '' }}"
                   data-bs-toggle="collapse" href="#sub-jersey"
                   aria-expanded="{{ $jerseyActive ? 'true' : 'false' }}">
                    <span class="toggle-left">
                        <i class="fas fa-tshirt"></i> Jersey
                    </span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="collapse {{ $jerseyActive ? 'show' : '' }}" id="sub-jersey">
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.jersey.index') || request()->routeIs('admin.jersey.create') || request()->routeIs('admin.jersey.show') || request()->routeIs('admin.jersey.edit') ? 'active' : '' }}"
                               href="{{ route('admin.jersey.index') }}">
                                <i class="fas fa-list"></i> Pesanan Jersey
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.jersey.report') ? 'active' : '' }}"
                               href="{{ route('admin.jersey.report') }}">
                                <i class="fas fa-chart-pie"></i> Laporan Jersey
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.master-ukuran-jersey.*') ? 'active' : '' }}"
                               href="{{ route('admin.master-ukuran-jersey.index') }}">
                                <i class="fas fa-ruler-horizontal"></i> Master Ukuran
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- ── LAPORAN & DATA ── --}}
            <li><div class="nav-section">Laporan & Data</div></li>

            {{-- Pendaftaran --}}
            @php $pendaftaranMenunggu = \App\Models\Pendaftaran::where('status','menunggu')->count(); @endphp
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.pendaftaran.*') ? 'active' : '' }}"
                   href="{{ route('admin.pendaftaran.index') }}">
                    <i class="fas fa-user-plus"></i>
                    Pendaftaran
                    @if($pendaftaranMenunggu > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendaftaranMenunggu }}</span>
                    @endif
                </a>
            </li>

            {{-- Pengguna --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                   href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users-cog"></i>
                    Pengguna
                </a>
            </li>            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}"
                   href="{{ route('admin.laporan.index') }}">
                    <i class="fas fa-chart-bar"></i> Laporan Keuangan
                </a>
            </li>

            @php
                $exportImportActive = request()->routeIs('admin.export.*') || request()->routeIs('admin.import.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link submenu-toggle {{ $exportImportActive ? 'active-parent' : '' }}"
                   data-bs-toggle="collapse" href="#sub-exportimport"
                   aria-expanded="{{ $exportImportActive ? 'true' : 'false' }}">
                    <span class="toggle-left">
                        <i class="fas fa-exchange-alt"></i> Export / Import
                    </span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="collapse {{ $exportImportActive ? 'show' : '' }}" id="sub-exportimport">
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.export.*') ? 'active' : '' }}"
                               href="{{ route('admin.export.index') }}">
                                <i class="fas fa-file-export"></i> Export Laporan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.import.*') ? 'active' : '' }}"
                               href="{{ route('admin.import.index') }}">
                                <i class="fas fa-file-import"></i> Import Siswa
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>{{-- end sidebar-nav --}}

</nav>

<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">

    <!-- Topbar -->
    <nav class="topbar d-flex justify-content-between align-items-center">
        <button class="btn btn-sm btn-outline-secondary d-md-none" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-muted small d-none d-md-inline">
                <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </span>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle text-dark text-decoration-none" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fa-lg me-1 text-danger"></i>
                    <span class="small fw-semibold">{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><span class="dropdown-item-text small text-muted">{{ Auth::user()->email }}</span></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user me-2 text-primary"></i> Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mobile sidebar toggle
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Persist scroll position of sidebar
    const sidebarNav = document.querySelector('.sidebar-nav');
    const scrollKey = 'sidebar_scroll';
    if (sidebarNav) {
        const saved = sessionStorage.getItem(scrollKey);
        if (saved) sidebarNav.scrollTop = parseInt(saved);
        sidebarNav.addEventListener('scroll', () => {
            sessionStorage.setItem(scrollKey, sidebarNav.scrollTop);
        });
    }
</script>

@stack('scripts')
</body>
</html>
