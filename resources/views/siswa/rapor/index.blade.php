<x-layouts.siswa>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-file-alt me-2 text-primary"></i>
            Rapor Saya
        </h4>
    </div>

    @if($rapor->isEmpty())
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Rapor</h5>
                <p class="text-muted">Rapor Anda akan muncul di sini setelah coach memberikan penilaian.</p>
            </div>
        </div>
    @else
        <!-- Tabel Rapor -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>
                    Daftar Rapor
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Periode</th>
                                <th>Teknik</th>
                                <th>Fisik</th>
                                <th>Kedisiplinan</th>
                                <th>Semangat</th>
                                <th>Rata-rata</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th>Coach</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rapor as $i => $r)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $r->periode }}</td>
                                    <td>
                                        <span class="badge bg-{{ $r->teknik_renang >= 8 ? 'success' : ($r->teknik_renang >= 6 ? 'warning' : 'danger') }}">
                                            {{ $r->teknik_renang }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $r->kondisi_fisik >= 8 ? 'success' : ($r->kondisi_fisik >= 6 ? 'warning' : 'danger') }}">
                                            {{ $r->kondisi_fisik }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $r->kedisiplinan >= 8 ? 'success' : ($r->kedisiplinan >= 6 ? 'warning' : 'danger') }}">
                                            {{ $r->kedisiplinan }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $r->semangat_berlatih >= 8 ? 'success' : ($r->semangat_berlatih >= 6 ? 'warning' : 'danger') }}">
                                            {{ $r->semangat_berlatih }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($r->rata_rata, 1) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $r->grade }}</span>
                                    </td>
                                    <td>
                                        @if($r->status === 'final')
                                            <span class="badge bg-success">Final</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $r->coach->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detail Rapor Cards -->
        @foreach($rapor as $r)
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-alt me-2"></i>
                        Rapor Periode: {{ $r->periode }}
                    </h6>
                    <div>
                        @if($r->status === 'final')
                            <span class="badge bg-success">Final</span>
                        @else
                            <span class="badge bg-warning text-dark">Draft</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row text-center">
                                <div class="col-6 col-md-3 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body py-3">
                                            <h3 class="text-success mb-0">{{ $r->teknik_renang }}</h3>
                                            <small class="text-muted">Teknik Renang</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body py-3">
                                            <h3 class="text-info mb-0">{{ $r->kondisi_fisik }}</h3>
                                            <small class="text-muted">Kondisi Fisik</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body py-3">
                                            <h3 class="text-warning mb-0">{{ $r->kedisiplinan }}</h3>
                                            <small class="text-muted">Kedisiplinan</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body py-3">
                                            <h3 class="text-danger mb-0">{{ $r->semangat_berlatih }}</h3>
                                            <small class="text-muted">Semangat</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bars -->
                            <div class="mt-2">
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <small>Teknik Renang</small>
                                        <small>{{ $r->teknik_renang }}/10</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: {{ $r->teknik_renang * 10 }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <small>Kondisi Fisik</small>
                                        <small>{{ $r->kondisi_fisik }}/10</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-info" style="width: {{ $r->kondisi_fisik * 10 }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <small>Kedisiplinan</small>
                                        <small>{{ $r->kedisiplinan }}/10</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $r->kedisiplinan * 10 }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <small>Semangat Berlatih</small>
                                        <small>{{ $r->semangat_berlatih }}/10</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-danger" style="width: {{ $r->semangat_berlatih * 10 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 text-center">
                            <div class="card border-0 bg-primary text-white h-100 d-flex align-items-center justify-content-center">
                                <div class="card-body">
                                    <h1 class="display-4 fw-bold mb-0">{{ $r->grade }}</h1>
                                    <p class="mb-1">Grade</p>
                                    <h4>{{ number_format($r->rata_rata, 2) }}</h4>
                                    <small>Rata-rata</small>
                                    <hr class="border-white">
                                    <small>Coach: {{ $r->coach->name ?? '-' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($r->catatan_coach)
                        <div class="mt-3">
                            <h6 class="text-muted">Catatan Coach:</h6>
                            <div class="alert alert-info">
                                <i class="fas fa-comment me-2"></i>
                                {{ $r->catatan_coach }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</x-layouts.siswa>
