<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\CatatanWaktu;
use App\Models\PersonalBest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return redirect()->route('profile.edit')
                ->with('error', 'Akun Anda belum terhubung dengan data siswa. Hubungi admin.');
        }

        $filterGaya = $request->input('gaya_renang');
        $filterJarak = $request->input('jarak');

        // Personal best records
        $personalBestQuery = PersonalBest::where('siswa_id', $siswa->id);

        if ($filterGaya) {
            $personalBestQuery->where('gaya_renang', $filterGaya);
        }
        if ($filterJarak) {
            $personalBestQuery->where('jarak', $filterJarak);
        }

        $personalBests = $personalBestQuery
            ->orderBy('gaya_renang')
            ->orderBy('jarak')
            ->get();

        // Catatan waktu lomba
        $catatanWaktuQuery = CatatanWaktu::where('siswa_id', $siswa->id)
            ->with('kejuaraan');

        if ($filterGaya) {
            $catatanWaktuQuery->where('gaya_renang', $filterGaya);
        }
        if ($filterJarak) {
            $catatanWaktuQuery->where('jarak', $filterJarak);
        }

        $catatanWaktu = $catatanWaktuQuery
            ->orderByDesc('created_at')
            ->get();

        // Daftar gaya renang dan jarak untuk filter
        $gayaList = PersonalBest::where('siswa_id', $siswa->id)
            ->distinct()
            ->pluck('gaya_renang')
            ->filter()
            ->sort()
            ->values();

        $jarakList = PersonalBest::where('siswa_id', $siswa->id)
            ->distinct()
            ->pluck('jarak')
            ->filter()
            ->sort()
            ->values();

        return view('siswa.prestasi.index', compact(
            'siswa',
            'personalBests',
            'catatanWaktu',
            'gayaList',
            'jarakList',
            'filterGaya',
            'filterJarak'
        ));
    }
}
