<x-layouts.admin>
    <x-page-header 
        title="Export Laporan"
        subtitle="Generate dan download laporan dalam format PDF dan Excel"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Export Laporan', 'url' => '#']
        ]"
    />

    <div class="row">
        <!-- Financial Report Export -->
        <div class="col-lg-6 mb-4">
            <x-form-group title="Laporan Keuangan" subtitle="Export laporan keuangan berdasarkan periode">
                <form action="{{ route('admin.export.financial-report') }}" method="POST" target="_blank">
                    @csrf
                    <x-form-input name="start_date" label="Tanggal Mulai" type="date" :value="date('Y-m-01')" required />
                    <x-form-input name="end_date" label="Tanggal Selesai" type="date" :value="date('Y-m-t')" required />
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-pdf me-2"></i> Export PDF
                        </button>
                    </div>
                </form>
                <form action="{{ route('admin.export.financial-report-excel') }}" method="POST" class="mt-3" id="form-financial-excel">
                    @csrf
                    <input type="hidden" name="start_date" id="financial_start_date_excel">
                    <input type="hidden" name="end_date" id="financial_end_date_excel">
                    <div class="d-grid">
                        <button type="button" class="btn btn-success" onclick="copyFinancialDates()">
                            <i class="fas fa-file-excel me-2"></i> Export Excel
                        </button>
                    </div>
                </form>
            </x-form-group>
        </div>

        <!-- Tuition Summary Export -->
        <div class="col-lg-6 mb-4">
            <x-form-group title="Laporan Iuran Bulanan" subtitle="Export ringkasan iuran siswa per bulan">
                <form action="{{ route('admin.export.tuition-summary') }}" method="POST" target="_blank" id="form-tuition-pdf">
                    @csrf
                    <x-form-input name="bulan" label="Bulan" type="select"
                        :options="[1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']"
                        :value="date('n')" required />
                    <x-form-input name="tahun" label="Tahun" type="number" :value="date('Y')" min="2020" max="2030" required />
                    <x-form-input name="kelas_id" label="Kelas (Opsional)" type="select" :options="$kelasList" />
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-file-pdf me-2"></i> Export PDF
                        </button>
                    </div>
                </form>
                <form action="{{ route('admin.export.tuition-summary-excel') }}" method="POST" class="mt-3" id="form-tuition-excel">
                    @csrf
                    <input type="hidden" name="bulan" id="tuition_bulan_excel">
                    <input type="hidden" name="tahun" id="tuition_tahun_excel">
                    <input type="hidden" name="kelas_id" id="tuition_kelas_excel">
                    <div class="d-grid">
                        <button type="button" class="btn btn-info" onclick="copyTuitionData()">
                            <i class="fas fa-file-excel me-2"></i> Export Excel
                        </button>
                    </div>
                </form>
            </x-form-group>
        </div>

        <!-- Attendance Report Export -->
        <div class="col-lg-6 mb-4">
            <x-form-group title="Laporan Kehadiran" subtitle="Export laporan kehadiran siswa">
                <form action="{{ route('admin.export.attendance-report') }}" method="POST" target="_blank" id="form-attendance-pdf">
                    @csrf
                    <x-form-input name="att_start_date" label="Tanggal Mulai" type="date" :value="date('Y-m-01')" required />
                    <x-form-input name="att_end_date" label="Tanggal Selesai" type="date" :value="date('Y-m-t')" required />
                    <x-form-input name="att_kelas_id" label="Kelas (Opsional)" type="select" :options="$kelasList" />
                    <x-form-input name="min_attendance" label="Minimum Kehadiran (%)" type="number" value="0" min="0" max="100" help="Filter siswa dengan kehadiran minimum tertentu" />
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-file-pdf me-2"></i> Export PDF
                        </button>
                    </div>
                </form>
                <form action="{{ route('admin.export.attendance-report-excel') }}" method="POST" class="mt-3" id="form-attendance-excel">
                    @csrf
                    <input type="hidden" name="att_start_date" id="att_start_excel">
                    <input type="hidden" name="att_end_date" id="att_end_excel">
                    <input type="hidden" name="att_kelas_id" id="att_kelas_excel">
                    <input type="hidden" name="min_attendance" id="att_min_excel">
                    <div class="d-grid">
                        <button type="button" class="btn btn-success" onclick="copyAttendanceData()">
                            <i class="fas fa-file-excel me-2"></i> Export Excel
                        </button>
                    </div>
                </form>
            </x-form-group>
        </div>

        <!-- Student Report Card Export -->
        <div class="col-lg-6 mb-4">
            <x-form-group title="Rapor Siswa" subtitle="Export rapor individual siswa">
                <form action="{{ route('admin.export.student-report-card') }}" method="POST" target="_blank" id="form-rapor-pdf">
                    @csrf
                    <x-form-input name="siswa_id" label="Pilih Siswa" type="select" :options="$siswaList" required />
                    <x-form-input name="periode" label="Periode (Opsional)" type="text" placeholder="Contoh: 2026-04" help="Kosongkan untuk semua periode" />
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-file-pdf me-2"></i> Export PDF
                        </button>
                    </div>
                </form>
                <form action="{{ route('admin.export.student-report-card-excel') }}" method="POST" class="mt-3" id="form-rapor-excel">
                    @csrf
                    <input type="hidden" name="siswa_id" id="rapor_siswa_excel">
                    <input type="hidden" name="periode" id="rapor_periode_excel">
                    <div class="d-grid">
                        <button type="button" class="btn btn-success" onclick="copyRaporData()">
                            <i class="fas fa-file-excel me-2"></i> Export Excel
                        </button>
                    </div>
                </form>
            </x-form-group>
        </div>

        <!-- Student List Export -->
        <div class="col-lg-6 mb-4">
            <x-form-group title="Daftar Siswa" subtitle="Export daftar siswa dalam format Excel">
                <form action="{{ route('admin.export.student-list-excel') }}" method="POST">
                    @csrf
                    <x-form-input name="kelas_id" label="Kelas (Opsional)" type="select" :options="$kelasList" />
                    <x-form-input name="status" label="Status (Opsional)" type="select"
                        :options="['aktif' => 'Aktif', 'cuti' => 'Cuti', 'nonaktif' => 'Non-aktif']" />
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-excel me-2"></i> Export Daftar Siswa
                        </button>
                    </div>
                </form>
            </x-form-group>
        </div>
    </div>

    <!-- Export Instructions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i> Petunjuk Export
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Format Laporan:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i> Laporan Keuangan: Format A4 Portrait</li>
                                <li><i class="fas fa-check text-success me-2"></i> Laporan Iuran: Format A4 Portrait</li>
                                <li><i class="fas fa-check text-success me-2"></i> Laporan Kehadiran: Format A4 Landscape</li>
                                <li><i class="fas fa-check text-success me-2"></i> Rapor Siswa: Format A4 Portrait</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Fitur Laporan:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i> Header dengan nama klub</li>
                                <li><i class="fas fa-check text-success me-2"></i> Format mata uang Rupiah</li>
                                <li><i class="fas fa-check text-success me-2"></i> Format tanggal Indonesia</li>
                                <li><i class="fas fa-check text-success me-2"></i> Export Excel dengan multiple sheets</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyFinancialDates() {
            document.getElementById('financial_start_date_excel').value = document.querySelector('#form-financial-excel').previousElementSibling.querySelector('[name="start_date"]').value;
            document.getElementById('financial_end_date_excel').value = document.querySelector('#form-financial-excel').previousElementSibling.querySelector('[name="end_date"]').value;
            document.getElementById('form-financial-excel').submit();
        }
        function copyTuitionData() {
            document.getElementById('tuition_bulan_excel').value = document.querySelector('#form-tuition-pdf [name="bulan"]').value;
            document.getElementById('tuition_tahun_excel').value = document.querySelector('#form-tuition-pdf [name="tahun"]').value;
            document.getElementById('tuition_kelas_excel').value = document.querySelector('#form-tuition-pdf [name="kelas_id"]').value;
            document.getElementById('form-tuition-excel').submit();
        }
        function copyAttendanceData() {
            document.getElementById('att_start_excel').value = document.querySelector('#form-attendance-pdf [name="att_start_date"]').value;
            document.getElementById('att_end_excel').value = document.querySelector('#form-attendance-pdf [name="att_end_date"]').value;
            document.getElementById('att_kelas_excel').value = document.querySelector('#form-attendance-pdf [name="att_kelas_id"]').value;
            document.getElementById('att_min_excel').value = document.querySelector('#form-attendance-pdf [name="min_attendance"]').value;
            document.getElementById('form-attendance-excel').submit();
        }
        function copyRaporData() {
            document.getElementById('rapor_siswa_excel').value = document.querySelector('#form-rapor-pdf [name="siswa_id"]').value;
            document.getElementById('rapor_periode_excel').value = document.querySelector('#form-rapor-pdf [name="periode"]').value;
            document.getElementById('form-rapor-excel').submit();
        }
    </script>
</x-layouts.admin>
