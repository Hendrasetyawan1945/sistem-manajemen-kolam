<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    /**
     * Display a listing of the resource (Rekap Kehadiran).
     */
    public function index(Request $request)
    {
        // Date range for attendance calculation
        $tanggalDari = $request->filled('tanggal_dari') 
            ? Carbon::parse($request->tanggal_dari) 
            : Carbon::now()->subMonth();
            
        $tanggalSampai = $request->filled('tanggal_sampai') 
            ? Carbon::parse($request->tanggal_sampai) 
            : Carbon::now();

        $query = Siswa::with(['kelas'])->where('status', 'aktif');
        
        // Filter by kelas_id
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        $siswaList = $query->orderBy('nama')->get();
        
        // Calculate attendance for each student
        $attendanceData = $siswaList->map(function($siswa) use ($tanggalDari, $tanggalSampai) {
            $kehadiranInRange = $siswa->kehadiran()
                ->whereHas('sesi', function($q) use ($tanggalDari, $tanggalSampai) {
                    $q->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
                })
                ->get();
            
            $totalSesi = $kehadiranInRange->count();
            $hadir = $kehadiranInRange->where('status', 'hadir')->count();
            $izin = $kehadiranInRange->where('status', 'izin')->count();
            $sakit = $kehadiranInRange->where('status', 'sakit')->count();
            $alpha = $kehadiranInRange->where('status', 'alpha')->count();
            $persentase = $totalSesi > 0 ? ($hadir / $totalSesi) * 100 : 0;
            
            return [
                'siswa' => $siswa,
                'total_sesi' => $totalSesi,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'persentase' => round($persentase, 1),
            ];
        });
        
        // Filter by minimum attendance if specified
        if ($request->filled('min_attendance')) {
            $minAttendance = $request->min_attendance;
            $attendanceData = $attendanceData->filter(function($item) use ($minAttendance) {
                return $item['persentase'] >= $minAttendance;
            });
        }
        
        // Sort by attendance percentage
        $attendanceData = $attendanceData->sortByDesc('persentase');
        
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();
        
        return view('admin.kehadiran.index', compact('attendanceData', 'kelasList', 'tanggalDari', 'tanggalSampai'));
    }
}
