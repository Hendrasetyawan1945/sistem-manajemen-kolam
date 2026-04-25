<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    /**
     * Display attendance recap for coach's classes.
     */
    public function index(Request $request)
    {
        $coachKelasIds = Kelas::where('coach_id', auth()->id())->pluck('id');

        $query = Siswa::with(['kelas', 'kehadiran.sesi'])
                     ->whereIn('kelas_id', $coachKelasIds)
                     ->where('status', 'aktif');

        if ($request->filled('kelas_id')) {
            if ($coachKelasIds->contains($request->kelas_id)) {
                $query->where('kelas_id', $request->kelas_id);
            }
        }

        $tanggalDari = $request->filled('tanggal_dari')
            ? Carbon::parse($request->tanggal_dari)
            : Carbon::now()->subMonth();

        $tanggalSampai = $request->filled('tanggal_sampai')
            ? Carbon::parse($request->tanggal_sampai)
            : Carbon::now();

        $siswaList = $query->get();

        // Hitung kehadiran per siswa
        $attendanceData = $siswaList->map(function ($siswa) use ($tanggalDari, $tanggalSampai) {
            $kehadiranInRange = $siswa->kehadiran()
                ->whereHas('sesi', function ($q) use ($tanggalDari, $tanggalSampai) {
                    $q->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
                })
                ->get();

            $totalSesi = $kehadiranInRange->count();
            $hadir     = $kehadiranInRange->where('status', 'hadir')->count();
            $izin      = $kehadiranInRange->where('status', 'izin')->count();
            $sakit     = $kehadiranInRange->where('status', 'sakit')->count();
            $alpha     = $kehadiranInRange->where('status', 'alpha')->count();
            $persentase = $totalSesi > 0 ? ($hadir / $totalSesi) * 100 : 0;

            return [
                'siswa'      => $siswa,
                'total_sesi' => $totalSesi,
                'hadir'      => $hadir,
                'izin'       => $izin,
                'sakit'      => $sakit,
                'alpha'      => $alpha,
                'persentase' => round($persentase, 1),
            ];
        });

        // Filter minimum attendance
        if ($request->filled('min_attendance')) {
            $minAttendance = $request->min_attendance;
            $attendanceData = $attendanceData->filter(function ($item) use ($minAttendance) {
                return $item['persentase'] >= $minAttendance;
            });
        }

        $attendanceData = $attendanceData->sortByDesc('persentase');

        $kelasList = Kelas::where('coach_id', auth()->id())
                         ->where('is_active', true)
                         ->orderBy('nama')
                         ->get();

        return view('coach.kehadiran.index', compact('attendanceData', 'kelasList', 'tanggalDari', 'tanggalSampai'));
    }
}
