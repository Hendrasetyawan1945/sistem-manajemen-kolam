@props([
    'title',
    'subtitle' => null,
    'columns' => 1
])

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>
        @if($subtitle)
            <small class="text-muted">{{ $subtitle }}</small>
        @endif
    </div>
    <div class="card-body">
        @if($columns > 1)
            <div class="row">
                {{ $slot }}
            </div>
        @else
            {{ $slot }}
        @endif
    </div>
</div>