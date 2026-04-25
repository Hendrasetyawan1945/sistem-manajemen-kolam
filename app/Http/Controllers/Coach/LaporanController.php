<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Sesi;
use App\Models\Siswa;
use App\Models\CatatanWaktu;
use App\Models\PersonalBest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $coachId = Auth::id();

        $kelasList = Kelas::where('coach_id', $coachId)
            ->where('is_active', true)
            ->orderBy('nama')
            ->get();

        $kelasIds = $kelasList->pluck('id');

        // Filter
        $kelasId     = $request->input('kelas_id');
        $tanggalDari = $request->filled('tanggal_dari')
            ? Carbon::parse($request->tanggal_dari)
            : Carbon::now()->startOfMonth();
        $tanggalSampai = $request->filled('tanggal_sampai')
            ? Carbon::parse($request->tanggal_sampai)
            : Carbon::now();

        // Kelas yang difilter (hanya kelas milik coach)
        $filteredKelasIds = $kelasId
            ? collect([$kelasId])->intersect($kelasIds)
            : $kelasIds;

        // ── Rekap Kehadiran per Siswa ──
        $siswaList = Siswa::whereIn('kelas_id', $filteredKelasIds)
            ->where('status', 'aktif')
            ->with('kelas')
            ->orderBy('nama')
            ->get();

        $rekapKehadiran = $siswaList->map(function ($siswa) use ($tanggalDari, $tanggalSampai) {
            $kehadiran = $siswa->kehadiran()
                ->whereHas('sesi', fn($q) => $q->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]))
                ->get();

            $total  = $kehadiran->count();
            $hadir  = $kehadiran->where('status', 'hadir')->count();
            $izin   = $kehadiran->where('status', 'izin')->count();
            $sakit  = $kehadiran->where('status', 'sakit')->count();
            $alpha  = $kehadiran->where('status', 'alpha')->count();
            $persen = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            return compact('siswa', 'total', 'hadir', 'izin', 'sakit', 'alpha', 'persen');
        })->sortByDesc('persen');

        // ── Rekap Sesi ──
        $totalSesi = Sesi::whereIn('kelas_id', $filteredKelasIds)
            ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->count();

        // ── Personal Best siswa di kelas coach ──
        $siswaIds = $siswaList->pluck('id');
        $personalBests = PersonalBest::whereIn('siswa_id', $siswaIds)
            ->with('siswa.kelas')
            ->orderBy('siswa_id')
            ->orderBy('gaya_renang')
            ->get()
            ->groupBy('siswa_id');

        return view('coach.laporan.index', compact(
            'kelasList', 'kelasId',
            'tanggalDari', 'tanggalSampai',
            'rekapKehadiran', 'totalSesi',
            'personalBests', 'siswaList'
        ));
    }
}
