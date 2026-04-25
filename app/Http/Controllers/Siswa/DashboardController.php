<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\PersonalBest;
use App\Models\Rapor;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return redirect()->route('profile.edit')
                ->with('error', 'Akun Anda belum terhubung dengan data siswa. Hubungi admin.');
        }

        $siswa->load('kelas');

        // Upcoming sessions: sesi dari kelas siswa, 7 hari ke depan
        $upcomingSessions = Sesi::where('kelas_id', $siswa->kelas_id)
            ->whereBetween('tanggal', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->with('kelas')
            ->get();

        // Attendance history bulan ini
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $kehadiranBulanIni = Kehadiran::where('siswa_id', $siswa->id)
            ->whereHas('sesi', function ($q) use ($bulanIni, $tahunIni) {
                $q->whereMonth('tanggal', $bulanIni)
                  ->whereYear('tanggal', $tahunIni);
            })
            ->with('sesi')
            ->get();

        $totalSesi = $kehadiranBulanIni->count();
        $totalHadir = $kehadiranBulanIni->where('status', 'hadir')->count();
        $attendancePercentage = $totalSesi > 0 ? round(($totalHadir / $totalSesi) * 100) : 0;

        // Recent iuran rutin (3 bulan terakhir)
        $recentIuran = $siswa->iuranRutin()
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->limit(3)
            ->get();

        // Iuran bulan ini
        $iuranBulanIni = $siswa->iuranRutin()
            ->where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->first();

        // Personal bests
        $personalBests = PersonalBest::where('siswa_id', $siswa->id)
            ->orderBy('gaya_renang')
            ->orderBy('jarak')
            ->get();

        $latestPersonalBest = $personalBests->first();

        // Rapor terbaru
        $latestRapor = Rapor::where('siswa_id', $siswa->id)
            ->orderByDesc('periode')
            ->first();

        return view('siswa.dashboard', compact(
            'siswa',
            'upcomingSessions',
            'attendancePercentage',
            'totalSesi',
            'totalHadir',
            'recentIuran',
            'iuranBulanIni',
            'personalBests',
            'latestPersonalBest',
            'latestRapor'
        ));
    }
}
