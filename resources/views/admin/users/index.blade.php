<x-layouts.admin>
    <x-page-header
        title="Manajemen Pengguna"
        subtitle="Kelola akun Admin dan Coach"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pengguna', 'url' => '#']
        ]"
    />

    <x-alert />

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-danger shadow">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="fas fa-user-shield text-danger fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-xs text-muted text-uppercase">Admin</div>
                        <div class="h4 mb-0 fw-bold">{{ $totalAdmin }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-primary shadow">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-chalkboard-teacher text-primary fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-xs text-muted text-uppercase">Coach</div>
                        <div class="h4 mb-0 fw-bold">{{ $totalCoach }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-users text-success fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-xs text-muted text-uppercase">Siswa</div>
                        <div class="h4 mb-0 fw-bold">{{ $totalSiswa }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna</h6>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Pengguna
            </a>
        </div>
        <div class="card-body">
            {{-- Filter --}}
            <form method="GET" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama atau email..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="role" class="form-select">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="coach" {{ request('role') == 'coach' ? 'selected' : '' }}>Coach</option>
                            <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Telepon</th>
                                <th>Status</th>
                                <th width="130">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                 style="width:36px;height:36px;font-size:14px;flex-shrink:0;
                                                 background:{{ $user->role === 'admin' ? '#dc3545' : ($user->role === 'coach' ? '#0d6efd' : '#198754') }}">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->id === Auth::id())
                                                    <span class="badge bg-secondary ms-1">Anda</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @php
                                            $roleColor = match($user->role) {
                                                'admin' => 'danger',
                                                'coach' => 'primary',
                                                default => 'success',
                                            };
                                            $roleLabel = match($user->role) {
                                                'admin' => 'Admin',
                                                'coach' => 'Coach',
                                                default => 'Siswa',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $roleColor }}">{{ $roleLabel }}</span>
                                    </td>
                                    <td>{{ $user->telepon ?? '-' }}</td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($user->role !== 'siswa')
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if($user->id !== Auth::id())
                                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-{{ $user->is_active ? 'secondary' : 'success' }}"
                                                    title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                    onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?')">
                                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $users->appends(request()->query())->links() }}</div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada pengguna ditemukan</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
