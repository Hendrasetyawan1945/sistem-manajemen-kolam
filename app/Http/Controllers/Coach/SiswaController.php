<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    private function getCoachKelasIds()
    {
        return Kelas::where('coach_id', Auth::id())->pluck('id');
    }

    public function index(Request $request)
    {
        $kelasIds = $this->getCoachKelasIds();
        $kelasList = Kelas::where('coach_id', Auth::id())->orderBy('nama')->get();

        $query = Siswa::with(['kelas', 'user'])
            ->whereIn('kelas_id', $kelasIds);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('nama', 'like', "%$search%")
                ->orWhere('nama_ortu', 'like', "%$search%"));
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $siswa = $query->orderBy('nama')->paginate(20);

        return view('coach.siswa.index', compact('siswa', 'kelasList'));
    }

    public function show(Siswa $siswa)
    {
        // Pastikan siswa ada di kelas coach
        $kelasIds = $this->getCoachKelasIds();
        abort_unless($kelasIds->contains($siswa->kelas_id), 403, 'Akses ditolak');

        $siswa->load(['kelas', 'kehadiran.sesi', 'personalBest', 'rapor']);

        $totalSesi   = $siswa->kehadiran()->count();
        $hadirCount  = $siswa->kehadiran()->where('status', 'hadir')->count();
        $attendance  = $totalSesi > 0 ? round(($hadirCount / $totalSesi) * 100, 1) : 0;

        return view('coach.siswa.show', compact('siswa', 'attendance'));
    }
}
