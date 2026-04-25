@props([
    'size' => 'md',
    'text' => 'Memuat...',
    'overlay' => false
])

@php
    $sizeClasses = [
        'sm' => 'spinner-border-sm',
        'md' => '',
        'lg' => 'spinner-border-lg'
    ];
@endphp

@if($overlay)
    <div class="loading-overlay position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
         style="background-color: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="text-center text-white">
            <div class="spinner-border {{ $sizeClasses[$size] }}" role="status">
                <span class="visually-hidden">{{ $text }}</span>
            </div>
            <div class="mt-2">{{ $text }}</div>
        </div>
    </div>
@else
    <div class="text-center py-4">
        <div class="spinner-border {{ $sizeClasses[$size] }} text-primary" role="status">
            <span class="visually-hidden">{{ $text }}</span>
        </div>
        <div class="mt-2 text-muted">{{ $text }}</div>
    </div>
@endif

@push('styles')
<style>
.loading-overlay {
    backdrop-filter: blur(2px);
}
</style>
@endpush