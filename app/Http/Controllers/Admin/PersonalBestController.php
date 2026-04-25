<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonalBest;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PersonalBestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PersonalBest::with(['siswa.kelas']);
        
        // Filter by siswa_id
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter by gaya_renang
        if ($request->filled('gaya_renang')) {
            $query->where('gaya_renang', $request->gaya_renang);
        }
        
        // Filter by jarak
        if ($request->filled('jarak')) {
            $query->where('jarak', $request->jarak);
        }
        
        $personalBest = $query->orderBy('siswa_id')
                             ->orderBy('gaya_renang')
                             ->orderBy('jarak')
                             ->paginate(20);
        
        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        
        // Gaya renang options
        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        
        // Jarak options
        $jarakList = [50, 100, 200, 400, 800, 1500];
        
        return view('admin.personal-best.index', compact('personalBest', 'siswaList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonalBest $personalBest)
    {
        $personalBest->load(['siswa.kelas']);
        
        // Get all catatan waktu for this siswa and nomor lomba
        $catatanWaktuHistory = \App\Models\CatatanWaktu::where([
            'siswa_id' => $personalBest->siswa_id,
            'gaya_renang' => $personalBest->gaya_renang,
            'jarak' => $personalBest->jarak,
        ])->with('kejuaraan')
          ->orderBy('created_at', 'desc')
          ->get();
        
        return view('admin.personal-best.show', compact('personalBest', 'catatanWaktuHistory'));
    }
}
