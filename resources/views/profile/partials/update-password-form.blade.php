<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    @if(session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> Password berhasil diperbarui.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-4">
            <label for="current_password" class="form-label fw-semibold">
                <i class="fas fa-lock me-1 text-muted"></i> Password Saat Ini
            </label>
            <div class="input-group">
                <input type="password" id="current_password" name="current_password"
                    class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif"
                    autocomplete="current-password"
                    placeholder="••••••••">
                <button class="btn btn-outline-secondary toggle-pw" type="button" data-target="current_password">
                    <i class="fas fa-eye"></i>
                </button>
                @if($errors->updatePassword->has('current_password'))
                    <div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <label for="password" class="form-label fw-semibold">
                <i class="fas fa-key me-1 text-muted"></i> Password Baru
            </label>
            <div class="input-group">
                <input type="password" id="password" name="password"
                    class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif"
                    autocomplete="new-password"
                    placeholder="Min. 8 karakter">
                <button class="btn btn-outline-secondary toggle-pw" type="button" data-target="password">
                    <i class="fas fa-eye"></i>
                </button>
                @if($errors->updatePassword->has('password'))
                    <div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <label for="password_confirmation" class="form-label fw-semibold">
                <i class="fas fa-check-double me-1 text-muted"></i> Konfirmasi Password
            </label>
            <div class="input-group">
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif"
                    autocomplete="new-password"
                    placeholder="Ulangi password baru">
                <button class="btn btn-outline-secondary toggle-pw" type="button" data-target="password_confirmation">
                    <i class="fas fa-eye"></i>
                </button>
                @if($errors->updatePassword->has('password_confirmation'))
                    <div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Password strength indicator --}}
    <div class="mt-2" id="pw-strength-wrap" style="display:none">
        <div class="d-flex align-items-center gap-2">
            <div class="progress flex-grow-1" style="height:6px">
                <div class="progress-bar" id="pw-strength-bar" style="width:0%"></div>
            </div>
            <small id="pw-strength-label" class="text-muted" style="min-width:60px"></small>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-warning px-4">
            <i class="fas fa-shield-alt me-2"></i> Perbarui Password
        </button>
    </div>
</form>

<script>
// Toggle show/hide password
document.querySelectorAll('.toggle-pw').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = document.getElementById(this.dataset.target);
        const icon  = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});

// Password strength
const pwInput = document.getElementById('password');
const bar     = document.getElementById('pw-strength-bar');
const label   = document.getElementById('pw-strength-label');
const wrap    = document.getElementById('pw-strength-wrap');

if (pwInput) {
    pwInput.addEventListener('input', function() {
        const val = this.value;
        wrap.style.display = val.length ? 'block' : 'none';

        let score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { pct: 25,  cls: 'bg-danger',  txt: 'Lemah' },
            { pct: 50,  cls: 'bg-warning', txt: 'Cukup' },
            { pct: 75,  cls: 'bg-info',    txt: 'Baik' },
            { pct: 100, cls: 'bg-success', txt: 'Kuat' },
        ];
        const lvl = levels[Math.max(0, score - 1)];
        bar.style.width = lvl.pct + '%';
        bar.className   = 'progress-bar ' + lvl.cls;
        label.textContent = lvl.txt;
        label.className   = 'text-' + lvl.cls.replace('bg-', '');
    });
}
</script>
