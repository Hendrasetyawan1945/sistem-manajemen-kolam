<x-layouts.coach>
    <x-page-header 
        title="Detail Rapor"
        subtitle="Informasi lengkap rapor siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Rapor', 'url' => route('coach.rapor.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Rapor</h6>
                    <div>
                        <a href="{{ route('coach.rapor.edit', $rapor) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Periode</th>
                            <td><strong>{{ $rapor->periode }}</strong></td>
                        </tr>
                        <tr>
                            <th>Siswa</th>
                            <td><strong>{{ $rapor->siswa->nama }}</strong></td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $rapor->siswa->kelas->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Coach</th>
                            <td>{{ $rapor->coach->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($rapor->status == 'final')
                                    <span class="badge bg-success">Final</span>
                                @else
                                    <span class="badge bg-warning">Draft</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <hr>

                    <h6 class="font-weight-bold text-primary mb-3">Penilaian</h6>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Aspek Penilaian</th>
                                <th width="100" class="text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Teknik Renang</td>
                                <td class="text-center"><strong class="text-primary">{{ $rapor->teknik_renang }}</strong></td>
                            </tr>
                            <tr>
                                <td>Kondisi Fisik</td>
                                <td class="text-center"><strong class="text-primary">{{ $rapor->kondisi_fisik }}</strong></td>
                            </tr>
                            <tr>
                                <td>Kedisiplinan</td>
                                <td class="text-center"><strong class="text-primary">{{ $rapor->kedisiplinan }}</strong></td>
                            </tr>
                            <tr>
                                <td>Semangat Berlatih</td>
                                <td class="text-center"><strong class="text-primary">{{ $rapor->semangat_berlatih }}</strong></td>
                            </tr>
                            <tr class="table-secondary">
                                <th>Rata-rata</th>
                                <th class="text-center"><strong class="text-success">{{ number_format($rapor->rata_rata, 2) }}</strong></th>
                            </tr>
                        </tbody>
                    </table>

                    @if($rapor->catatan_coach)
                        <div class="mt-3">
                            <h6 class="font-weight-bold text-primary">Catatan Coach</h6>
                            <div class="alert alert-info">
                                {{ $rapor->catatan_coach }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4 border-left-success">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                    <h6 class="text-muted">Grade</h6>
                    <h1 class="text-success font-weight-bold">{{ $rapor->grade }}</h1>
                    <p class="text-muted mb-0">Rata-rata: {{ number_format($rapor->rata_rata, 2) }}</p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Skala Nilai</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><span class="badge bg-success">A</span></td>
                            <td>9.0 - 10.0</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-info">B+</span></td>
                            <td>8.0 - 8.9</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-info">B</span></td>
                            <td>7.0 - 7.9</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-warning">C+</span></td>
                            <td>6.0 - 6.9</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-warning">C</span></td>
                            <td>5.0 - 5.9</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-danger">D</span></td>
                            <td>&lt; 5.0</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('coach.rapor.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</x-layouts.coach>
