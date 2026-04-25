<x-layouts.admin>
    <x-page-header
        title="Detail Pengguna"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pengguna', 'url' => route('admin.users.index')],
            ['title' => $user->name, 'url' => '#']
        ]"
    />

    <x-alert />

    <div class="row">
        {{-- Info Pengguna --}}
        <div class="col-md-4">
            <div class="card shadow mb-4 text-center p-4">
                @php
                    $roleColor = match($user->role) { 'admin' => 'danger', 'coach' => 'primary', default => 'success' };
                    $roleLabel = match($user->role) { 'admin' => 'Admin', 'coach' => 'Coach', default => 'Siswa' };
                @endphp
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3"
                     style="width:80px;height:80px;font-size:28px;background:var(--bs-{{ $roleColor }})">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <span class="badge bg-{{ $roleColor }} mb-2">{{ $roleLabel }}</span>
                <br>
                @if($user->is_active)
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-secondary">Nonaktif</span>
                @endif
            </div>

            {{-- Aksi --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    @if($user->role !== 'siswa')
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Edit Data
                        </a>
                    @endif

                    @if($user->id !== Auth::id())
                        {{-- Toggle Status --}}
                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }} w-100"
                                onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                                <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} me-2"></i>
                                {{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                            </button>
                        </form>

                        {{-- Hapus --}}
                        @if($user->role !== 'siswa')
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus akun {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash me-2"></i> Hapus Akun
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Info Detail --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" width="35%">Nama</td>
                            <td><strong>{{ $user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Telepon</td>
                            <td>{{ $user->telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Role</td>
                            <td><span class="badge bg-{{ $roleColor }}">{{ $roleLabel }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Bergabung</td>
                            <td>{{ formatTanggal($user->created_at) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Kelas (jika coach) --}}
            @if($user->role === 'coach' && $user->kelasAsCoach->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-chalkboard me-2"></i>Kelas yang Diampu
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($user->kelasAsCoach as $kelas)
                                <div class="col-md-6">
                                    <div class="border rounded p-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $kelas->nama }}</strong>
                                            <br><small class="text-muted">{{ $kelas->jadwal ?? '-' }}</small>
                                        </div>
                                        <span class="badge bg-{{ $kelas->is_active ? 'success' : 'secondary' }}">
                                            {{ $kelas->siswa_count }} siswa
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Reset Password --}}
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-warning">
                        <i class="fas fa-key me-2"></i>Reset Password
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="new_pw" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min. 8 karakter" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePw('new_pw', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Konfirmasi</label>
                                <div class="input-group">
                                    <input type="password" id="new_pw2" name="password_confirmation"
                                        class="form-control" placeholder="Ulangi password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePw('new_pw2', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-warning w-100"
                                    onclick="return confirm('Reset password {{ $user->name }}?')">
                                    <i class="fas fa-sync me-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</x-layouts.admin>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}
</script>
