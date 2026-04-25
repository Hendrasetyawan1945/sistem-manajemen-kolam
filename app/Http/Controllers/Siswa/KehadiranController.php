<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return redirect()->route('profile.edit')
                ->with('error', 'Akun Anda belum terhubung dengan data siswa. Hubungi admin.');
        }

        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $kehadiran = Kehadiran::where('siswa_id', $siswa->id)
            ->whereHas('sesi', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
            })
            ->with(['sesi.kelas'])
            ->get()
            ->sortByDesc(fn($k) => $k->sesi?->tanggal);

        // Summary
        $totalSesi = $kehadiran->count();
        $totalHadir = $kehadiran->where('status', 'hadir')->count();
        $totalIzin = $kehadiran->where('status', 'izin')->count();
        $totalSakit = $kehadiran->where('status', 'sakit')->count();
        $totalAlpha = $kehadiran->where('status', 'alpha')->count();
        $persentaseKehadiran = $totalSesi > 0 ? round(($totalHadir / $totalSesi) * 100) : 0;

        // Daftar tahun untuk filter (SQLite compatible)
        $tahunList = Kehadiran::where('siswa_id', $siswa->id)
            ->join('sesi', 'kehadiran.sesi_id', '=', 'sesi.id')
            ->selectRaw("strftime('%Y', sesi.tanggal) as tahun")
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->map(fn($t) => (int) $t)
            ->toArray();

        if (empty($tahunList)) {
            $tahunList = [now()->year];
        }

        return view('siswa.kehadiran.index', compact(
            'siswa',
            'kehadiran',
            'bulan',
            'tahun',
            'tahunList',
            'totalSesi',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlpha',
            'persentaseKehadiran'
        ));
    }
}
