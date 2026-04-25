<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Sesi;
use App\Models\Kehadiran;
use App\Models\Siswa;
use App\Models\CatatanWaktu;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $coachId = Auth::user()->id;
        $bulanIni = Carbon::now();

        // Kelas yang di-assign ke coach ini
        $kelasList = Kelas::where('coach_id', $coachId)
            ->where('is_active', true)
            ->withCount('siswa')
            ->get();

        $kelasIds = $kelasList->pluck('id');

        // Total siswa di kelas coach
        $totalSiswa = Siswa::whereIn('kelas_id', $kelasIds)
            ->where('status', 'aktif')
            ->count();

        // Sesi bulan ini untuk kelas coach
        $sesibulanIni = Sesi::whereIn('kelas_id', $kelasIds)
            ->whereYear('tanggal', $bulanIni->year)
            ->whereMonth('tanggal', $bulanIni->month)
            ->count();

        // Upcoming sessions (7 hari ke depan) untuk kelas coach
        $upcomingSessions = Sesi::whereIn('kelas_id', $kelasIds)
            ->whereBetween('tanggal', [Carbon::today(), Carbon::today()->addDays(7)])
            ->with(['kelas'])
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get();

        // Recent attendance (10 terakhir) untuk kelas coach
        $recentAttendance = Sesi::whereIn('kelas_id', $kelasIds)
            ->with(['kelas', 'kehadiran'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($sesi) {
                $totalSiswa = $sesi->kehadiran->count();
                $hadirCount = $sesi->kehadiran->where('status', 'hadir')->count();
                $persentase = $totalSiswa > 0 ? round(($hadirCount / $totalSiswa) * 100, 1) : 0;

                return [
                    'sesi'       => $sesi,
                    'total'      => $totalSiswa,
                    'hadir'      => $hadirCount,
                    'persentase' => $persentase,
                ];
            });

        // Siswa dengan kehadiran < 75% di kelas coach (bulan ini)
        $lowAttendanceStudents = $this->getLowAttendanceStudents($kelasIds, $bulanIni);

        // Recent catatan waktu/prestasi untuk siswa di kelas coach
        $recentCompetitionResults = CatatanWaktu::whereHas('siswa', function ($query) use ($kelasIds) {
                $query->whereIn('kelas_id', $kelasIds);
            })
            ->with(['siswa.kelas', 'kejuaraan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('coach.dashboard', compact(
            'kelasList',
            'totalSiswa',
            'sesibulanIni',
            'upcomingSessions',
            'recentAttendance',
            'lowAttendanceStudents',
            'recentCompetitionResults'
        ));
    }

    private function getLowAttendanceStudents($kelasIds, $bulanIni)
    {
        $siswaList = Siswa::whereIn('kelas_id', $kelasIds)
            ->where('status', 'aktif')
            ->with('kelas')
            ->get()
            ->map(function ($siswa) use ($bulanIni) {
                $totalSesi = $siswa->kehadiran()
                    ->whereHas('sesi', function ($query) use ($bulanIni) {
                        $query->whereYear('tanggal', $bulanIni->year)
                              ->whereMonth('tanggal', $bulanIni->month);
                    })
                    ->count();

                $hadirCount = $siswa->kehadiran()
                    ->whereHas('sesi', function ($query) use ($bulanIni) {
                        $query->whereYear('tanggal', $bulanIni->year)
                              ->whereMonth('tanggal', $bulanIni->month);
                    })
                    ->where('status', 'hadir')
                    ->count();

                $persentase = $totalSesi > 0 ? round(($hadirCount / $totalSesi) * 100, 1) : 100;

                return [
                    'siswa'      => $siswa,
                    'persentase' => $persentase,
                    'total_sesi' => $totalSesi,
                    'hadir'      => $hadirCount,
                ];
            })
            ->filter(function ($item) {
                return $item['persentase'] < 75 && $item['total_sesi'] > 0;
            })
            ->sortBy('persentase')
            ->take(10);

        return $siswaList;
    }
}
