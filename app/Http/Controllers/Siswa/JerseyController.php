<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jersey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JerseyController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return redirect()->route('profile.edit')
                ->with('error', 'Akun Anda belum terhubung dengan data siswa. Hubungi admin.');
        }

        $jerseyOrders = Jersey::where('siswa_id', $siswa->id)
            ->with('masterUkuranJersey')
            ->orderByDesc('tanggal_pesan')
            ->get();

        return view('siswa.jersey.index', compact('siswa', 'jerseyOrders'));
    }
}
