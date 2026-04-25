<div class="d-flex align-items-start gap-3">
    <div class="flex-grow-1">
        <p class="text-muted small mb-3">
            Setelah akun dihapus, semua data akan dihapus permanen.
            Pastikan Anda sudah menyimpan data penting sebelum melanjutkan.
        </p>
        <button type="button" class="btn btn-outline-danger btn-sm"
            data-bs-toggle="modal" data-bs-target="#modalHapusAkun">
            <i class="fas fa-trash me-2"></i> Hapus Akun Saya
        </button>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div class="modal fade" id="modalHapusAkun" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i> Hapus Akun
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Tindakan ini <strong>tidak dapat dibatalkan</strong>.
                        Masukkan password Anda untuk konfirmasi.
                    </p>
                    <div class="mb-3">
                        <label for="delete_password" class="form-label fw-semibold">Password</label>
                        <input type="password" id="delete_password" name="password"
                            class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif"
                            placeholder="Masukkan password Anda" required>
                        @if($errors->userDeletion->has('password'))
                            <div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('modalHapusAkun')).show();
    });
</script>
@endif
