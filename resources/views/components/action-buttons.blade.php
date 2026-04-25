@props([
    'editRoute' => null,
    'deleteRoute' => null,
    'viewRoute' => null,
    'customActions' => [],
    'size' => 'sm'
])

<div class="d-flex flex-nowrap gap-1">
    @if($viewRoute)
        <a href="{{ $viewRoute }}" class="btn btn-info btn-{{ $size }}" title="Lihat Detail">
            <i class="fas fa-eye"></i>
        </a>
    @endif

    @if($editRoute)
        <a href="{{ $editRoute }}" class="btn btn-warning btn-{{ $size }}" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
    @endif

    @foreach($customActions as $action)
        <a href="{{ $action['route'] }}"
           class="btn btn-{{ $action['color'] ?? 'secondary' }} btn-{{ $size }}"
           title="{{ $action['title'] ?? '' }}"
           @if(isset($action['confirm']))
               onclick="return confirm('{{ $action['confirm'] }}')"
           @endif>
            <i class="{{ $action['icon'] }}"></i>
        </a>
    @endforeach

    @if($deleteRoute)
        <form method="POST" action="{{ $deleteRoute }}" class="d-flex"
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-{{ $size }}" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endif
</div>