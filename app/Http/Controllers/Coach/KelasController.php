<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of coach's own classes.
     */
    public function index()
    {
        $kelas = Kelas::where('coach_id', auth()->id())
                     ->withCount('siswa')
                     ->with('siswa')
                     ->orderBy('nama')
                     ->paginate(20);

        return view('coach.kelas.index', compact('kelas'));
    }

    /**
     * Display the specified class (only if owned by coach).
     */
    public function show($id)
    {
        $kelas = Kelas::where('coach_id', auth()->id())
                     ->findOrFail($id);

        $kelas->load(['siswa' => function ($q) {
            $q->orderBy('nama');
        }, 'sesi' => function ($q) {
            $q->orderBy('tanggal', 'desc')->limit(10);
        }]);

        $totalSesi   = $kelas->sesi()->count();
        $totalSiswa  = $kelas->siswa()->count();
        $siswaAktif  = $kelas->siswa()->where('status', 'aktif')->count();
        $kapasitasTerisi = $totalSiswa > 0 ? ($totalSiswa / $kelas->kapasitas) * 100 : 0;

        return view('coach.kelas.show', compact('kelas', 'totalSesi', 'totalSiswa', 'siswaAktif', 'kapasitasTerisi'));
    }
}
