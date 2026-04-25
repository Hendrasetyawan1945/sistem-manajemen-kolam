<x-layouts.admin>
    <x-page-header
        title="Tambah Pengguna"
        subtitle="Buat akun Admin atau Coach baru"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pengguna', 'url' => route('admin.users.index')],
            ['title' => 'Tambah', 'url' => '#']
        ]"
    />

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-plus me-2"></i>Form Pengguna Baru
                    </h6>
                </div>
                <div class="card-body p-4">

                    <div class="alert alert-info py-2 mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Halaman ini untuk membuat akun <strong>Admin</strong> atau <strong>Coach</strong>.
                        Untuk akun Siswa, gunakan menu <a href="{{ route('admin.pendaftaran.index') }}">Pendaftaran</a> atau <a href="{{ route('admin.siswa.create') }}">Tambah Siswa</a>.
                    </div>

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="Nama lengkap" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="">Pilih Role</option>
                                    <option value="coach" {{ old('role') == 'coach' ? 'selected' : '' }}>
                                        🏊 Coach
                                    </option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                        🛡️ Admin
                                    </option>
                                </select>
                                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="email@contoh.com" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">No. Telepon</label>
                                <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                                    value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx">
                                @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="pw" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min. 8 karakter" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePw('pw', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="pw2" name="password_confirmation"
                                        class="form-control" placeholder="Ulangi password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePw('pw2', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Buat Akun
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
