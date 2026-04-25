<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')

    @if(session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> Profil berhasil diperbarui.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label for="name" class="form-label fw-semibold">
                <i class="fas fa-user me-1 text-muted"></i> Nama Lengkap
            </label>
            <input type="text" id="name" name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}"
                required autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label fw-semibold">
                <i class="fas fa-envelope me-1 text-muted"></i> Email
            </label>
            <input type="email" id="email" name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}"
                required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i> Simpan Perubahan
        </button>
    </div>
</form>
