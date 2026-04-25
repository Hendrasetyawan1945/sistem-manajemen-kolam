@props([
    'status',
    'type' => 'default',
    'size' => 'sm'
])

@php
    $badgeClasses = [
        'aktif' => 'bg-success',
        'nonaktif' => 'bg-danger',
        'cuti' => 'bg-warning',
        'lunas' => 'bg-success',
        'belum' => 'bg-danger',
        'cicilan' => 'bg-warning',
        'hadir' => 'bg-success',
        'alpha' => 'bg-danger',
        'izin' => 'bg-info',
        'sakit' => 'bg-warning',
        'dipesan' => 'bg-info',
        'diterima' => 'bg-success',
        'dibatalkan' => 'bg-danger',
        'pending' => 'bg-warning',
        'approved' => 'bg-success',
        'rejected' => 'bg-danger',
        'default' => 'bg-secondary'
    ];
    
    $statusText = [
        'aktif' => 'Aktif',
        'nonaktif' => 'Non Aktif',
        'cuti' => 'Cuti',
        'lunas' => 'Lunas',
        'belum' => 'Belum Lunas',
        'cicilan' => 'Cicilan',
        'hadir' => 'Hadir',
        'alpha' => 'Alpha',
        'izin' => 'Izin',
        'sakit' => 'Sakit',
        'dipesan' => 'Dipesan',
        'diterima' => 'Diterima',
        'dibatalkan' => 'Dibatalkan',
        'pending' => 'Pending',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak'
    ];
    
    $badgeClass = $badgeClasses[strtolower($status)] ?? $badgeClasses['default'];
    $displayText = $statusText[strtolower($status)] ?? ucfirst($status);
@endphp

<span class="badge {{ $badgeClass }} badge-{{ $size }}">
    {{ $displayText }}
</span>