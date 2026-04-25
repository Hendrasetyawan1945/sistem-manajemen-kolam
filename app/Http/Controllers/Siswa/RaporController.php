<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Rapor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaporController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return redirect()->route('profile.edit')
                ->with('error', 'Akun Anda belum terhubung dengan data siswa. Hubungi admin.');
        }

        $rapor = Rapor::where('siswa_id', $siswa->id)
            ->with('coach')
            ->orderByDesc('periode')
            ->get();

        return view('siswa.rapor.index', compact('siswa', 'rapor'));
    }
}
