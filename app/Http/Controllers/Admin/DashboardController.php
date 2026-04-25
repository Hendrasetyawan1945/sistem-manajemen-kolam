<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\IuranRutin;
use App\Models\IuranInsidentil;
use App\Models\IuranKejuaraan;
use App\Models\Angsuran;
use App\Models\Pengeluaran;
use App\Models\Kejuaraan;
use App\Models\Kehadiran;
use App\Models\Sesi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total siswa aktif
        $totalSiswaAktif = Siswa::where('status', 'aktif')->count();
        
        // Total kelas aktif
        $totalKelas = Kelas::where('is_active', true)->count();
        
        // Total coaches aktif
        $totalCoaches = User::where('role', 'coach')
                           ->where('is_active', true)
                           ->count();
        
        // Pendapatan bulan ini
        $bulanIni = Carbon::now();
        $pendapatanBulanIni = $this->calculatePendapatanBulanIni($bulanIni);
        
        // Pengeluaran bulan ini
        $pengeluaranBulanIni = Pengeluaran::whereYear('tanggal', $bulanIni->year)
                                         ->whereMonth('tanggal', $bulanIni->month)
                                         ->sum('jumlah');
        
        // Net balance
        $netBalance = $pendapatanBulanIni - $pengeluaranBulanIni;
        
        // Outstanding tuition (iuran yang belum dibayar)
        $outstandingTuition = $this->calculateOutstandingTuition();
        
        // Upcoming competitions (30 hari ke depan)
        $upcomingCompetitions = Kejuaraan::where('tanggal_mulai', '>=', Carbon::now())
                                        ->where('tanggal_mulai', '<=', Carbon::now()->addDays(30))
                                        ->with('iuranKejuaraan.siswa')
                                        ->orderBy('tanggal_mulai')
                                        ->get();
        
        // Recent transactions (10 terakhir)
        $recentTransactions = $this->getRecentTransactions();
        
        // Attendance statistics bulan ini
        $attendanceStats = $this->getAttendanceStats($bulanIni);
        
        // Siswa dengan kehadiran rendah (<75%)
        $lowAttendanceStudents = $this->getLowAttendanceStudents();
        
        return view('admin.dashboard', compact(
            'totalSiswaAktif',
            'totalKelas', 
            'totalCoaches',
            'pendapatanBulanIni',
            'pengeluaranBulanIni',
            'netBalance',
            'outstandingTuition',
            'upcomingCompetitions',
            'recentTransactions',
            'attendanceStats',
            'lowAttendanceStudents'
        ));
    }
    
    private function calculatePendapatanBulanIni($bulanIni)
    {
        // Iuran rutin yang sudah dibayar bulan ini
        $iuranRutin = IuranRutin::where('status_bayar', 'lunas')
                               ->whereYear('tanggal_bayar', $bulanIni->year)
                               ->whereMonth('tanggal_bayar', $bulanIni->month)
                               ->sum('jumlah');
        
        // Iuran insidentil yang sudah dibayar bulan ini
        $iuranInsidentil = IuranInsidentil::where('status_bayar', 'lunas')
                                        ->whereYear('tanggal_bayar', $bulanIni->year)
                                        ->whereMonth('tanggal_bayar', $bulanIni->month)
                                        ->sum('jumlah');
        
        // Iuran kejuaraan yang sudah dibayar bulan ini
        $iuranKejuaraan = IuranKejuaraan::where('status_bayar', 'lunas')
                                       ->whereYear('tanggal_bayar', $bulanIni->year)
                                       ->whereMonth('tanggal_bayar', $bulanIni->month)
                                       ->sum('jumlah');
        
        // Angsuran yang dibayar bulan ini
        $angsuran = Angsuran::whereHas('detailAngsuran', function($query) use ($bulanIni) {
                           $query->whereYear('tanggal_bayar', $bulanIni->year)
                                 ->whereMonth('tanggal_bayar', $bulanIni->month);
                       })
                       ->with(['detailAngsuran' => function($query) use ($bulanIni) {
                           $query->whereYear('tanggal_bayar', $bulanIni->year)
                                 ->whereMonth('tanggal_bayar', $bulanIni->month);
                       }])
                       ->get()
                       ->sum(function($angsuran) {
                           return $angsuran->detailAngsuran->sum('jumlah_bayar');
                       });
        
        return $iuranRutin + $iuranInsidentil + $iuranKejuaraan + $angsuran;
    }
    
    private function calculateOutstandingTuition()
    {
        // Iuran rutin yang belum dibayar
        $iuranRutinBelum = IuranRutin::where('status_bayar', 'belum')->sum('jumlah');
        
        // Iuran insidentil yang belum dibayar
        $iuranInsidentilBelum = IuranInsidentil::where('status_bayar', 'belum')->sum('jumlah');
        
        // Iuran kejuaraan yang belum dibayar
        $iuranKejuaraanBelum = IuranKejuaraan::where('status_bayar', 'belum')->sum('jumlah');
        
        // Sisa angsuran yang belum dibayar
        $sisaAngsuran = Angsuran::where('status', 'aktif')->sum('sisa');
        
        return $iuranRutinBelum + $iuranInsidentilBelum + $iuranKejuaraanBelum + $sisaAngsuran;
    }
    
    private function getRecentTransactions()
    {
        $transactions = collect();
        
        // Iuran rutin terbaru
        $iuranRutin = IuranRutin::where('status_bayar', 'lunas')
                               ->with('siswa')
                               ->orderBy('tanggal_bayar', 'desc')
                               ->limit(5)
                               ->get()
                               ->map(function($item) {
                                   return [
                                       'tanggal' => $item->tanggal_bayar,
                                       'siswa' => $item->siswa->nama,
                                       'jenis' => 'Iuran Rutin',
                                       'jumlah' => $item->jumlah,
                                       'status' => 'lunas'
                                   ];
                               });
        
        // Iuran kejuaraan terbaru
        $iuranKejuaraan = IuranKejuaraan::where('status_bayar', 'lunas')
                                       ->with(['siswa', 'kejuaraan'])
                                       ->orderBy('tanggal_bayar', 'desc')
                                       ->limit(5)
                                       ->get()
                                       ->map(function($item) {
                                           return [
                                               'tanggal' => $item->tanggal_bayar,
                                               'siswa' => $item->siswa->nama,
                                               'jenis' => 'Iuran Kejuaraan',
                                               'jumlah' => $item->jumlah,
                                               'status' => 'lunas'
                                           ];
                                       });
        
        $transactions = $transactions->merge($iuranRutin)->merge($iuranKejuaraan);
        
        return $transactions->sortByDesc('tanggal')->take(10);
    }
    
    private function getAttendanceStats($bulanIni)
    {
        // Total sesi bulan ini
        $totalSesi = Sesi::whereYear('tanggal', $bulanIni->year)
                        ->whereMonth('tanggal', $bulanIni->month)
                        ->count();
        
        // Total kehadiran bulan ini
        $totalKehadiran = Kehadiran::whereHas('sesi', function($query) use ($bulanIni) {
                                  $query->whereYear('tanggal', $bulanIni->year)
                                        ->whereMonth('tanggal', $bulanIni->month);
                              })
                              ->count();
        
        // Kehadiran berdasarkan status
        $kehadiranHadir = Kehadiran::whereHas('sesi', function($query) use ($bulanIni) {
                                  $query->whereYear('tanggal', $bulanIni->year)
                                        ->whereMonth('tanggal', $bulanIni->month);
                              })
                              ->where('status', 'hadir')
                              ->count();
        
        $kehadiranAlpha = Kehadiran::whereHas('sesi', function($query) use ($bulanIni) {
                                  $query->whereYear('tanggal', $bulanIni->year)
                                        ->whereMonth('tanggal', $bulanIni->month);
                              })
                              ->where('status', 'alpha')
                              ->count();
        
        $kehadiranIzin = Kehadiran::whereHas('sesi', function($query) use ($bulanIni) {
                                 $query->whereYear('tanggal', $bulanIni->year)
                                       ->whereMonth('tanggal', $bulanIni->month);
                             })
                             ->where('status', 'izin')
                             ->count();
        
        $kehadiranSakit = Kehadiran::whereHas('sesi', function($query) use ($bulanIni) {
                                  $query->whereYear('tanggal', $bulanIni->year)
                                        ->whereMonth('tanggal', $bulanIni->month);
                              })
                              ->where('status', 'sakit')
                              ->count();
        
        $persentaseKehadiran = $totalKehadiran > 0 ? ($kehadiranHadir / $totalKehadiran) * 100 : 0;
        
        return [
            'total_sesi' => $totalSesi,
            'total_kehadiran' => $totalKehadiran,
            'hadir' => $kehadiranHadir,
            'alpha' => $kehadiranAlpha,
            'izin' => $kehadiranIzin,
            'sakit' => $kehadiranSakit,
            'persentase' => round($persentaseKehadiran, 1)
        ];
    }
    
    private function getLowAttendanceStudents()
    {
        // Ambil siswa dengan kehadiran < 75% dalam 30 hari terakhir
        $siswaList = Siswa::with(['kehadiran.sesi', 'kelas'])
                          ->where('status', 'aktif')
                          ->get()
                          ->map(function($siswa) {
                              $totalSesi = $siswa->kehadiran()
                                                ->whereHas('sesi', function($query) {
                                                    $query->where('tanggal', '>=', Carbon::now()->subDays(30));
                                                })
                                                ->count();
                              
                              $hadirCount = $siswa->kehadiran()
                                                 ->whereHas('sesi', function($query) {
                                                     $query->where('tanggal', '>=', Carbon::now()->subDays(30));
                                                 })
                                                 ->where('status', 'hadir')
                                                 ->count();
                              
                              $persentase = $totalSesi > 0 ? ($hadirCount / $totalSesi) * 100 : 100;
                              
                              return [
                                  'siswa' => $siswa,
                                  'persentase' => round($persentase, 1),
                                  'total_sesi' => $totalSesi,
                                  'hadir' => $hadirCount
                              ];
                          })
                          ->filter(function($item) {
                              return $item['persentase'] < 75 && $item['total_sesi'] > 0;
                          })
                          ->sortBy('persentase')
                          ->take(5);
        
        return $siswaList;
    }
}