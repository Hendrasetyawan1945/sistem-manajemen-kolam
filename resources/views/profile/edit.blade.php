@php
    $user  = Auth::user();
    $role  = $user->role ?? 'siswa';
    $layout = match($role) {
        'admin' => 'layouts.admin',
        'coach' => 'layouts.coach',
        default => 'layouts.siswa',
    };
    $dashboardRoute = match($role) {
        'admin' => 'admin.dashboard',
        'coach' => 'coach.dashboard',
        default => 'siswa.dashboard',
    };
    $roleColor = match($role) {
        'admin' => 'danger',
        'coach' => 'primary',
        default => 'success',
    };
    $roleLabel = match($role) {
        'admin' => 'Administrator',
        'coach' => 'Coach',
        default => 'Siswa',
    };
@endphp

@component($layout)
@slot('slot')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item">
                    <a href="{{ route($dashboardRoute) }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0">Pengaturan Akun</h4>
    </div>
</div>

<div class="row">
    {{-- Sidebar Profile --}}
    <div class="col-md-3 mb-4">
        <div class="card shadow text-center p-4">
            {{-- Avatar --}}
            <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                 style="width:80px;height:80px;font-size:28px;background:var(--bs-{{ $roleColor }})">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h6 class="fw-bold mb-1">{{ $user->name }}</h6>
            <span class="badge bg-{{ $roleColor }} mb-2">{{ $roleLabel }}</span>
            <p class="text-muted small mb-0">{{ $user->email }}</p>
        </div>

        {{-- Nav tabs --}}
        <div class="list-group mt-3 shadow-sm" id="profile-tabs" role="tablist">
            <a class="list-group-item list-group-item-action active d-flex align-items-center gap-2"
               id="tab-info" data-bs-toggle="list" href="#panel-info" role="tab">
                <i class="fas fa-user-circle text-{{ $roleColor }}"></i>
                <span>Informasi Akun</span>
            </a>
            <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
               id="tab-password" data-bs-toggle="list" href="#panel-password" role="tab">
                <i class="fas fa-shield-alt text-warning"></i>
                <span>Ubah Password</span>
            </a>
            <a class="list-group-item list-group-item-action d-flex align-items-center gap-2 text-danger"
               id="tab-delete" data-bs-toggle="list" href="#panel-delete" role="tab">
                <i class="fas fa-trash-alt"></i>
                <span>Hapus Akun</span>
            </a>
        </div>
    </div>

    {{-- Content Panels --}}
    <div class="col-md-9">
        <div class="tab-content">

            {{-- Panel: Informasi Akun --}}
            <div class="tab-pane fade show active" id="panel-info" role="tabpanel">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle text-{{ $roleColor }}"></i>
                        <h6 class="m-0 fw-bold">Informasi Akun</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">
                            Perbarui nama dan alamat email akun Anda.
                        </p>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Panel: Ubah Password --}}
            <div class="tab-pane fade" id="panel-password" role="tabpanel">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex align-items-center gap-2">
                        <i class="fas fa-shield-alt text-warning"></i>
                        <h6 class="m-0 fw-bold">Ubah Password</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">
                            Gunakan password yang panjang dan acak agar akun Anda tetap aman.
                        </p>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Panel: Hapus Akun --}}
            <div class="tab-pane fade" id="panel-delete" role="tabpanel">
                <div class="card shadow border-danger">
                    <div class="card-header py-3 d-flex align-items-center gap-2 bg-danger bg-opacity-10">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                        <h6 class="m-0 fw-bold text-danger">Zona Berbahaya</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">
                            Setelah akun dihapus, semua data akan hilang permanen dan tidak dapat dipulihkan.
                        </p>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Auto-open tab jika ada error password atau delete --}}
@if($errors->updatePassword->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-password')).show();
    });
</script>
@endif

@endslot
@endcomponent
