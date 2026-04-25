<x-layouts.admin>
    <x-page-header 
        title="Detail Ukuran Jersey"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Ukuran Jersey', 'url' => route('admin.master-ukuran-jersey.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detail Ukuran Jersey</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.master-ukuran-jersey.edit', $masterUkuranJersey->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('admin.master-ukuran-jersey.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <td class="text-muted" width="30%">Ukuran</td>
                    <td><strong>{{ $masterUkuranJersey->ukuran }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Keterangan</td>
                    <td>{{ $masterUkuranJersey->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Total Pesanan</td>
                    <td>{{ $masterUkuranJersey->jersey->count() }} pesanan</td>
                </tr>
            </table>
        </div>
    </div>
</x-layouts.admin>
