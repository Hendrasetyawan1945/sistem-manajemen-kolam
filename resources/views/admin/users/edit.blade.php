<x-layouts.admin>
    <x-page-header
        title="Edit Pengguna"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pengguna', 'url' => route('admin.users.index')],
            ['title' => $user->name, 'url' => route('admin.users.show', $user->id)],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Edit Data Pengguna
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Role</label>
                                <input type="text" class="form-control bg-light"
                                    value="{{ ucfirst($user->role) }}" readonly>
                                <small class="text-muted">Role tidak bisa diubah</small>
                            </div>

                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">No. Telepon</label>
                                <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                                    value="{{ old('telepon', $user->telepon) }}" placeholder="08xxxxxxxxxx">
                                @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="alert alert-info py-2 mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Untuk reset password, gunakan tombol di halaman <a href="{{ route('admin.users.show', $user->id) }}">Detail Pengguna</a>.
                        </div>

                        <hr class="my-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
