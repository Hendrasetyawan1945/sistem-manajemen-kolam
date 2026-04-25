@props(['errors' => null])

@php
    $errors = $errors ?? $errors ?? session('errors');
@endphp

@if($errors && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Terdapat kesalahan pada form:</strong>
        </div>
        
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('database'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-database me-2"></i>
            <strong>Kesalahan Database:</strong>
        </div>
        
        <p class="mb-0">{{ session('database') }}</p>
        
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('system'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Kesalahan Sistem:</strong>
        </div>
        
        <p class="mb-0">{{ session('system') }}</p>
        
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif