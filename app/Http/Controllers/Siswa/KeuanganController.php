<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Angsuran;
use App\Models\IuranInsidentil;
use App\Models\IuranRutin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return redirect()->route('profile.edit')
                ->with('error', 'Akun Anda belum terhubung dengan data siswa. Hubungi admin.');
        }

        // Semua iuran rutin (tampilkan semua, highlight yang belum lunas)
        $iuranRutin = IuranRutin::where('siswa_id', $siswa->id)
            ->with('metodePembayaran')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        // Iuran insidentil belum lunas
        $iuranInsidentilBelumLunas = IuranInsidentil::where('siswa_id', $siswa->id)
            ->where('status_bayar', 'belum')
            ->orderByDesc('tanggal')
            ->get();

        // Semua iuran insidentil
        $iuranInsidentil = IuranInsidentil::where('siswa_id', $siswa->id)
            ->with('metodePembayaran')
            ->orderByDesc('tanggal')
            ->get();

        // Angsuran belum lunas
        $angsuranBelumLunas = Angsuran::where('siswa_id', $siswa->id)
            ->where('status', '!=', 'lunas')
            ->with('detailAngsuran.metodePembayaran')
            ->orderByDesc('created_at')
            ->get();

        // Semua angsuran
        $angsuran = Angsuran::where('siswa_id', $siswa->id)
            ->with('detailAngsuran.metodePembayaran')
            ->orderByDesc('created_at')
            ->get();

        // Total outstanding
        $totalOutstandingIuranRutin = $iuranRutin->where('status_bayar', 'belum')->sum('jumlah');
        $totalOutstandingInsidentil = $iuranInsidentilBelumLunas->sum('jumlah');
        $totalOutstandingAngsuran = $angsuranBelumLunas->sum('sisa');
        $totalOutstanding = $totalOutstandingIuranRutin + $totalOutstandingInsidentil + $totalOutstandingAngsuran;

        return view('siswa.keuangan.index', compact(
            'siswa',
            'iuranRutin',
            'iuranInsidentil',
            'angsuran',
            'totalOutstandingIuranRutin',
            'totalOutstandingInsidentil',
            'totalOutstandingAngsuran',
            'totalOutstanding'
        ));
    }
}
