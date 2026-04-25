@props([
    'title',
    'value',
    'icon' => 'fas fa-chart-line',
    'color' => 'primary',
    'subtitle' => null,
    'trend' => null,
    'trendDirection' => null
])

<div class="col-md-6 col-xl-3 mb-4">
    <div class="card border-left-{{ $color }} shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-{{ $color }} text-uppercase mb-1">
                        {{ $title }}
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $value }}
                    </div>
                    @if($subtitle)
                        <div class="text-xs text-muted mt-1">{{ $subtitle }}</div>
                    @endif
                    @if($trend && $trendDirection)
                        <div class="text-xs mt-1">
                            <i class="fas fa-arrow-{{ $trendDirection === 'up' ? 'up' : 'down' }} 
                               text-{{ $trendDirection === 'up' ? 'success' : 'danger' }}"></i>
                            <span class="text-{{ $trendDirection === 'up' ? 'success' : 'danger' }}">{{ $trend }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-auto">
                    <i class="{{ $icon }} fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>