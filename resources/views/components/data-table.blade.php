@props([
    'headers' => [],
    'data' => [],
    'actions' => true,
    'searchable' => true,
    'searchPlaceholder' => 'Cari data...',
    'emptyMessage' => 'Tidak ada data yang ditemukan',
    'createRoute' => null,
    'createText' => 'Tambah Data'
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Data Table' }}</h6>
        
        <div class="d-flex gap-2">
            @if($searchable)
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control form-control-sm" 
                           placeholder="{{ $searchPlaceholder }}" 
                           id="tableSearch">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            @endif
            
            @if($createRoute)
                <a href="{{ $createRoute }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> {{ $createText }}
                </a>
            @endif
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable">
                <thead class="table-light">
                    <tr>
                        @foreach($headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                        @if($actions)
                            <th width="120">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            {{ $slot }}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>{{ $emptyMessage }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($data, 'links'))
            <div class="d-flex justify-content-center mt-3">
                {{ $data->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('tableSearch');
    const table = document.getElementById('dataTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < cells.length - 1; j++) { // -1 to skip action column
                    if (cells[j].textContent.toLowerCase().includes(filter)) {
                        found = true;
                        break;
                    }
                }
                
                row.style.display = found ? '' : 'none';
            }
        });
    }
});
</script>
@endpush