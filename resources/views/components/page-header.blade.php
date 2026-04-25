@props([
    'title',
    'subtitle' => null,
    'breadcrumbs' => [],
    'actions' => null
])

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        @endif
        
        @if(count($breadcrumbs) > 0)
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb mb-0">
                    @foreach($breadcrumbs as $breadcrumb)
                        @if($loop->last)
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $breadcrumb['title'] }}
                            </li>
                        @else
                            <li class="breadcrumb-item">
                                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif
    </div>
    
    @if($actions)
        <div class="d-flex gap-2">
            {{ $actions }}
        </div>
    @endif
</div>