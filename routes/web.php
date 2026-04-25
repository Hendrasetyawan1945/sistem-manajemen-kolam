<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ExportPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

// Pendaftaran Siswa Baru (publik)
Route::get('/daftar', [\App\Http\Controllers\PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/daftar', [\App\Http\Controllers\PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/daftar/sukses', [\App\Http\Controllers\PendaftaranController::class, 'sukses'])->name('pendaftaran.sukses');
Route::get('/cek-status', [\App\Http\Controllers\PendaftaranController::class, 'cekStatus'])->name('pendaftaran.cek-status');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Siswa Management
    Route::resource('siswa', \App\Http\Controllers\Admin\SiswaController::class);
    
    // Kelas Management
    Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class);
    
    // Sesi Latihan Management
    Route::resource('sesi', \App\Http\Controllers\Admin\SesiController::class);
    Route::get('/sesi/{sesi}/attendance', [\App\Http\Controllers\Admin\SesiController::class, 'attendance'])->name('sesi.attendance');
    Route::post('/sesi/{sesi}/attendance', [\App\Http\Controllers\Admin\SesiController::class, 'updateAttendance'])->name('sesi.updateAttendance');
    
    // Kehadiran Management
    Route::resource('kehadiran', \App\Http\Controllers\Admin\KehadiranController::class);
    
    // Iuran Rutin
    Route::resource('iuran-rutin', \App\Http\Controllers\Admin\IuranRutinController::class);
    
    // Iuran Insidentil
    Route::resource('iuran-insidentil', \App\Http\Controllers\Admin\IuranInsidentilController::class);
    
    // Kejuaraan
    Route::resource('kejuaraan', \App\Http\Controllers\Admin\KejuaraanController::class);
    Route::resource('iuran-kejuaraan', \App\Http\Controllers\Admin\IuranKejuaraanController::class);
    
    // Angsuran
    Route::resource('angsuran', \App\Http\Controllers\Admin\AngsuranController::class);
    Route::post('/angsuran/{angsuran}/payment', [\App\Http\Controllers\Admin\AngsuranController::class, 'addPayment'])->name('angsuran.addPayment');
    Route::delete('/angsuran/{angsuran}/payment/{detail}', [\App\Http\Controllers\Admin\AngsuranController::class, 'deletePayment'])->name('angsuran.deletePayment');
    
    // Pengeluaran
    Route::resource('pengeluaran', \App\Http\Controllers\Admin\PengeluaranController::class);
    
    // Catatan Waktu
    Route::resource('catatan-waktu', \App\Http\Controllers\Admin\CatatanWaktuController::class);
    
    // Personal Best
    Route::resource('personal-best', \App\Http\Controllers\Admin\PersonalBestController::class);
    
    // Catatan Latihan
    Route::resource('catatan-latihan', \App\Http\Controllers\Admin\CatatanLatihanController::class);
    Route::get('/catatan-latihan-analytics', [\App\Http\Controllers\Admin\CatatanLatihanController::class, 'analytics'])->name('catatan-latihan.analytics');
    
    // Rapor
    Route::resource('rapor', \App\Http\Controllers\Admin\RaporController::class);
    
    // Master Ukuran Jersey
    Route::resource('master-ukuran-jersey', \App\Http\Controllers\Admin\MasterUkuranJerseyController::class);

    // Jersey
    Route::get('/jersey/report', [\App\Http\Controllers\Admin\JerseyController::class, 'report'])->name('jersey.report');
    Route::resource('jersey', \App\Http\Controllers\Admin\JerseyController::class);
    Route::patch('/jersey/{jersey}/status', [\App\Http\Controllers\Admin\JerseyController::class, 'updateStatus'])->name('jersey.updateStatus');
    
    // Laporan
    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/keuangan', [\App\Http\Controllers\Admin\LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    Route::get('/laporan/iuran-rutin', [\App\Http\Controllers\Admin\LaporanController::class, 'iuranRutin'])->name('laporan.iuran-rutin');
    Route::get('/laporan/keuangan/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'keuanganPdf'])->name('laporan.keuangan.pdf');
    Route::get('/laporan/iuran-rutin/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'iuranRutinPdf'])->name('laporan.iuran-rutin.pdf');
    Route::get('/laporan/kehadiran/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'kehadiranPdf'])->name('laporan.kehadiran.pdf');
    Route::get('/rapor/{rapor}/pdf', [\App\Http\Controllers\Admin\RaporController::class, 'pdf'])->name('rapor.pdf');
    
    // Export PDF Routes (New Implementation)
    Route::get('/export', [ExportPageController::class, 'index'])->name('export.index');
    Route::prefix('export')->name('export.')->group(function () {
        // PDF Exports
        Route::post('/financial-report', [ExportController::class, 'exportFinancialReport'])->name('financial-report');
        Route::post('/tuition-summary', [ExportController::class, 'exportTuitionSummary'])->name('tuition-summary');
        Route::post('/attendance-report', [ExportController::class, 'exportAttendanceReport'])->name('attendance-report');
        Route::post('/student-report-card', [ExportController::class, 'exportStudentReportCard'])->name('student-report-card');
        
        // Excel Exports
        Route::post('/financial-report-excel', [ExportController::class, 'exportFinancialReportExcel'])->name('financial-report-excel');
        Route::post('/tuition-summary-excel', [ExportController::class, 'exportTuitionSummaryExcel'])->name('tuition-summary-excel');
        Route::post('/attendance-report-excel', [ExportController::class, 'exportAttendanceReportExcel'])->name('attendance-report-excel');
        Route::post('/student-report-card-excel', [ExportController::class, 'exportStudentReportCardExcel'])->name('student-report-card-excel');
        Route::post('/student-list-excel', [ExportController::class, 'exportStudentListExcel'])->name('student-list-excel');
    });
    
    // Import CSV Routes
    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ImportController::class, 'index'])->name('index');
        Route::get('/template', [\App\Http\Controllers\Admin\ImportController::class, 'downloadTemplate'])->name('template');
        Route::post('/preview', [\App\Http\Controllers\Admin\ImportController::class, 'preview'])->name('preview');
        Route::post('/process', [\App\Http\Controllers\Admin\ImportController::class, 'import'])->name('process');
        Route::post('/export-csv', [\App\Http\Controllers\Admin\ImportController::class, 'exportCsv'])->name('export-csv');
    });

    // Pendaftaran Siswa (Admin Review)
    Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PendaftaranAdminController::class, 'index'])->name('index');
        Route::get('/{pendaftaran}', [\App\Http\Controllers\Admin\PendaftaranAdminController::class, 'show'])->name('show');
        Route::post('/{pendaftaran}/approve', [\App\Http\Controllers\Admin\PendaftaranAdminController::class, 'approve'])->name('approve');
        Route::post('/{pendaftaran}/reject', [\App\Http\Controllers\Admin\PendaftaranAdminController::class, 'reject'])->name('reject');
        Route::delete('/{pendaftaran}', [\App\Http\Controllers\Admin\PendaftaranAdminController::class, 'destroy'])->name('destroy');
    });

    // Manajemen Pengguna (Admin & Coach)
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::patch('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
});

// Coach Routes
Route::middleware(['auth', 'role:coach'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Coach\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('kelas', \App\Http\Controllers\Coach\KelasController::class)->only(['index', 'show']);
    Route::resource('siswa', \App\Http\Controllers\Coach\SiswaController::class)->only(['index', 'show']);
    Route::resource('sesi', \App\Http\Controllers\Coach\SesiController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/sesi/{id}/attendance', [\App\Http\Controllers\Coach\SesiController::class, 'updateAttendance'])->name('sesi.updateAttendance');
    Route::resource('kehadiran', \App\Http\Controllers\Coach\KehadiranController::class)->only(['index']);
    Route::resource('catatan-latihan', \App\Http\Controllers\Coach\CatatanLatihanController::class)->except(['show']);
    Route::resource('catatan-waktu', \App\Http\Controllers\Coach\CatatanWaktuController::class)->except(['show']);
    Route::resource('personal-best', \App\Http\Controllers\Coach\PersonalBestController::class)->only(['index']);
    Route::resource('rapor', \App\Http\Controllers\Coach\RaporController::class)->except(['destroy']);
    
    Route::get('/laporan', [\App\Http\Controllers\Coach\LaporanController::class, 'index'])->name('laporan.index');
});

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kehadiran', [\App\Http\Controllers\Siswa\KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('/keuangan', [\App\Http\Controllers\Siswa\KeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('/prestasi', [\App\Http\Controllers\Siswa\PrestasiController::class, 'index'])->name('prestasi.index');
    Route::get('/rapor', [\App\Http\Controllers\Siswa\RaporController::class, 'index'])->name('rapor.index');
    Route::get('/jersey', [\App\Http\Controllers\Siswa\JerseyController::class, 'index'])->name('jersey.index');
});

// Profile routes (available for all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
