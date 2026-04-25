<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\CatatanWaktu;
use App\Models\Kelas;
use App\Models\PersonalBest;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PersonalBestController extends Controller
{
    /**
     * Ambil daftar siswa_id yang ada di kelas coach.
     */
    private function getCoachSiswaIds()
    {
        $coachKelasIds = Kelas::where('coach_id', auth()->id())->pluck('id');
        return Siswa::whereIn('kelas_id', $coachKelasIds)->pluck('id');
    }

    /**
     * Display personal best records for coach's students.
     */
    public function index(Request $request)
    {
        $siswaIds = $this->getCoachSiswaIds();

        $query = PersonalBest::with(['siswa.kelas'])
                            ->whereIn('siswa_id', $siswaIds);

        if ($request->filled('siswa_id')) {
            if ($siswaIds->contains($request->siswa_id)) {
                $query->where('siswa_id', $request->siswa_id);
            }
        }

        if ($request->filled('gaya_renang')) {
            $query->where('gaya_renang', $request->gaya_renang);
        }

        if ($request->filled('jarak')) {
            $query->where('jarak', $request->jarak);
        }

        $personalBest = $query->orderBy('siswa_id')
                             ->orderBy('gaya_renang')
                             ->orderBy('jarak')
                             ->paginate(20);

        $kelasIds  = Kelas::where('coach_id', auth()->id())->pluck('id');
        $siswaList = Siswa::whereIn('kelas_id', $kelasIds)->where('status', 'aktif')->orderBy('nama')->get();

        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        $jarakList      = [50, 100, 200, 400, 800, 1500];

        return view('coach.personal-best.index', compact('personalBest', 'siswaList', 'gayaRenangList', 'jarakList'));
    }
}
