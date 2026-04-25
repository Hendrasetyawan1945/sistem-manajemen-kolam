<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IuranRutin;
use App\Models\IuranInsidentil;
use App\Models\IuranKejuaraan;
use App\Models\DetailAngsuran;
use App\Models\Pengeluaran;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MetodePembayaran;
use App\Models\ItemKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan
     */
    public function index()
    {
        return view('admin.laporan.index');
    }

    /**
     * Laporan keuangan komprehensif
     */
    public function keuangan(Request $request)
    {
        // Default: bulan ini
        $tanggalDari = $request->filled('tanggal_dari')
            ? $request->tanggal_dari
            : now()->startOfMonth()->toDateString();

        $tanggalSampai = $request->filled('tanggal_sampai')
            ? $request->tanggal_sampai
            : now()->endOfMonth()->toDateString();

        // ── INCOME ──────────────────────────────────────────────────────────

        // Iuran Rutin
        $totalIuranRutin = IuranRutin::where('status_bayar', 'lunas')
            ->whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->sum('jumlah');

        // Iuran Insidentil
        $totalIuranInsidentil = IuranInsidentil::where('status_bayar', 'lunas')
            ->whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->sum('jumlah');

        // Iuran Kejuaraan
        $totalIuranKejuaraan = IuranKejuaraan::where('status_bayar', 'lunas')
            ->whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->sum('jumlah');

        // Angsuran (detail_angsuran)
        $totalAngsuran = DetailAngsuran::whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->sum('jumlah_bayar');

        $totalIncome = $totalIuranRutin + $totalIuranInsidentil + $totalIuranKejuaraan + $totalAngsuran;

        // Income breakdown by category
        $incomeBreakdown = [
            ['kategori' => 'Iuran Rutin',      'jumlah' => $totalIuranRutin],
            ['kategori' => 'Iuran Insidentil',  'jumlah' => $totalIuranInsidentil],
            ['kategori' => 'Iuran Kejuaraan',   'jumlah' => $totalIuranKejuaraan],
            ['kategori' => 'Angsuran',          'jumlah' => $totalAngsuran],
        ];

        // ── EXPENSES ────────────────────────────────────────────────────────

        $totalExpenses = Pengeluaran::whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->sum('jumlah');

        // Expense breakdown by item_kas
        $expenseBreakdown = Pengeluaran::whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->select('item_kas_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('item_kas_id')
            ->with('itemKas')
            ->get();

        // ── NET BALANCE ─────────────────────────────────────────────────────

        $netBalance = $totalIncome - $totalExpenses;

        // ── PAYMENT METHOD DISTRIBUTION ─────────────────────────────────────

        // Gabungkan semua transaksi income per metode pembayaran
        $metodePembayaranList = MetodePembayaran::all()->keyBy('id');

        $paymentMethodRaw = [];

        // Iuran Rutin per metode
        $iuranRutinByMetode = IuranRutin::where('status_bayar', 'lunas')
            ->whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->select('metode_pembayaran_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('metode_pembayaran_id')
            ->get();

        foreach ($iuranRutinByMetode as $row) {
            $key = $row->metode_pembayaran_id ?? 0;
            $paymentMethodRaw[$key] = ($paymentMethodRaw[$key] ?? 0) + $row->total;
        }

        // Iuran Insidentil per metode
        $iuranInsidentilByMetode = IuranInsidentil::where('status_bayar', 'lunas')
            ->whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->select('metode_pembayaran_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('metode_pembayaran_id')
            ->get();

        foreach ($iuranInsidentilByMetode as $row) {
            $key = $row->metode_pembayaran_id ?? 0;
            $paymentMethodRaw[$key] = ($paymentMethodRaw[$key] ?? 0) + $row->total;
        }

        // Iuran Kejuaraan per metode
        $iuranKejuaraanByMetode = IuranKejuaraan::where('status_bayar', 'lunas')
            ->whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->select('metode_pembayaran_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('metode_pembayaran_id')
            ->get();

        foreach ($iuranKejuaraanByMetode as $row) {
            $key = $row->metode_pembayaran_id ?? 0;
            $paymentMethodRaw[$key] = ($paymentMethodRaw[$key] ?? 0) + $row->total;
        }

        // Detail Angsuran per metode
        $angsuranByMetode = DetailAngsuran::whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->select('metode_pembayaran_id', DB::raw('SUM(jumlah_bayar) as total'))
            ->groupBy('metode_pembayaran_id')
            ->get();

        foreach ($angsuranByMetode as $row) {
            $key = $row->metode_pembayaran_id ?? 0;
            $paymentMethodRaw[$key] = ($paymentMethodRaw[$key] ?? 0) + $row->total;
        }

        // Format payment method distribution
        $paymentMethodDistribution = [];
        foreach ($paymentMethodRaw as $metodeId => $total) {
            $nama = $metodeId && isset($metodePembayaranList[$metodeId])
                ? $metodePembayaranList[$metodeId]->nama
                : 'Tidak Diketahui';
            $paymentMethodDistribution[] = [
                'metode'     => $nama,
                'jumlah'     => $total,
                'persentase' => $totalIncome > 0 ? round(($total / $totalIncome) * 100, 1) : 0,
            ];
        }

        return view('admin.laporan.keuangan', compact(
            'tanggalDari',
            'tanggalSampai',
            'totalIncome',
            'totalExpenses',
            'netBalance',
            'incomeBreakdown',
            'expenseBreakdown',
            'paymentMethodDistribution'
        ));
    }

    /**
     * Laporan rekap iuran rutin
     */
    public function iuranRutin(Request $request)
    {
        $bulan  = $request->filled('bulan')  ? (int) $request->bulan  : (int) now()->format('m');
        $tahun  = $request->filled('tahun')  ? (int) $request->tahun  : (int) now()->format('Y');
        $kelasId = $request->filled('kelas_id') ? $request->kelas_id : null;

        // Ambil semua siswa aktif, filter by kelas jika ada
        $siswaQuery = Siswa::with(['kelas', 'iuranRutin' => function ($q) use ($bulan, $tahun) {
            $q->where('bulan', $bulan)->where('tahun', $tahun);
        }])->where('status', 'aktif');

        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }

        $siswaList = $siswaQuery->orderBy('nama')->get();

        // Build per-siswa data
        $data = [];
        $totalLunas        = 0;
        $totalBelumLunas   = 0;
        $totalTerkumpul    = 0;
        $totalOutstanding  = 0;

        foreach ($siswaList as $siswa) {
            $iuran = $siswa->iuranRutin->first();

            if ($iuran) {
                $status       = $iuran->status_bayar; // 'lunas' or 'belum'
                $jumlah       = $iuran->jumlah;
                $tanggalBayar = $iuran->tanggal_bayar;
                $metode       = $iuran->metodePembayaran->nama ?? '-';
            } else {
                $status       = 'belum';
                $jumlah       = $siswa->kelas->biaya_bulanan ?? 0;
                $tanggalBayar = null;
                $metode       = '-';
            }

            if ($status === 'lunas') {
                $totalLunas++;
                $totalTerkumpul += $jumlah;
            } else {
                $totalBelumLunas++;
                $totalOutstanding += $jumlah;
            }

            $data[] = [
                'siswa'        => $siswa,
                'kelas'        => $siswa->kelas,
                'status'       => $status,
                'jumlah'       => $jumlah,
                'tanggal_bayar' => $tanggalBayar,
                'metode'       => $metode,
            ];
        }

        $totalSiswa = count($data);

        // Daftar kelas untuk filter dropdown
        $kelasList = Kelas::orderBy('nama')->get();

        // Nama bulan untuk display
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('admin.laporan.iuran-rutin', compact(
            'bulan',
            'tahun',
            'kelasId',
            'data',
            'totalSiswa',
            'totalLunas',
            'totalBelumLunas',
            'totalTerkumpul',
            'totalOutstanding',
            'kelasList',
            'namaBulan'
        ));
    }

    /**
     * Export laporan keuangan ke PDF
     */
    public function keuanganPdf(Request $request)
    {
        $tanggalDari = $request->filled('tanggal_dari')
            ? $request->tanggal_dari
            : now()->startOfMonth()->toDateString();

        $tanggalSampai = $request->filled('tanggal_sampai')
            ? $request->tanggal_sampai
            : now()->endOfMonth()->toDateString();

        // Income
        $totalIuranRutin = IuranRutin::where('status_bayar', 'lunas')
            ->whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->sum('jumlah');

        $totalIuranInsidentil = IuranInsidentil::where('status_bayar', 'lunas')
            ->whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->sum('jumlah');

        $totalIuranKejuaraan = IuranKejuaraan::where('status_bayar', 'lunas')
            ->whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->sum('jumlah');

        $totalAngsuran = DetailAngsuran::whereBetween('tanggal_bayar', [$tanggalDari, $tanggalSampai])
            ->sum('jumlah_bayar');

        $totalIncome = $totalIuranRutin + $totalIuranInsidentil + $totalIuranKejuaraan + $totalAngsuran;

        $incomeBreakdown = [
            ['kategori' => 'Iuran Rutin',      'jumlah' => $totalIuranRutin],
            ['kategori' => 'Iuran Insidentil',  'jumlah' => $totalIuranInsidentil],
            ['kategori' => 'Iuran Kejuaraan',   'jumlah' => $totalIuranKejuaraan],
            ['kategori' => 'Angsuran',          'jumlah' => $totalAngsuran],
        ];

        // Expenses
        $totalExpenses = Pengeluaran::whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->sum('jumlah');

        $expenseBreakdown = Pengeluaran::whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->select('item_kas_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('item_kas_id')
            ->with('itemKas')
            ->get();

        $netBalance = $totalIncome - $totalExpenses;

        $namaKlub = config('app.name', 'Klub Renang');
        $tanggalCetak = now()->toDateString();

        $data = compact(
            'tanggalDari',
            'tanggalSampai',
            'totalIncome',
            'totalExpenses',
            'netBalance',
            'incomeBreakdown',
            'expenseBreakdown',
            'namaKlub',
            'tanggalCetak'
        );

        $filename = 'laporan-keuangan-' . $tanggalDari . '-sd-' . $tanggalSampai . '.pdf';

        return Pdf::loadView('admin.laporan.pdf.keuangan', $data)
            ->setPaper('a4', 'landscape')
            ->download($filename);
    }

    /**
     * Export rekap iuran rutin ke PDF
     */
    public function iuranRutinPdf(Request $request)
    {
        $bulan   = $request->filled('bulan')  ? (int) $request->bulan  : (int) now()->format('m');
        $tahun   = $request->filled('tahun')  ? (int) $request->tahun  : (int) now()->format('Y');
        $kelasId = $request->filled('kelas_id') ? $request->kelas_id : null;

        $siswaQuery = Siswa::with(['kelas', 'iuranRutin' => function ($q) use ($bulan, $tahun) {
            $q->where('bulan', $bulan)->where('tahun', $tahun);
        }])->where('status', 'aktif');

        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }

        $siswaList = $siswaQuery->orderBy('nama')->get();

        $data = [];
        $totalLunas       = 0;
        $totalBelumLunas  = 0;
        $totalTerkumpul   = 0;
        $totalOutstanding = 0;

        foreach ($siswaList as $siswa) {
            $iuran = $siswa->iuranRutin->first();

            if ($iuran) {
                $status       = $iuran->status_bayar;
                $jumlah       = $iuran->jumlah;
                $tanggalBayar = $iuran->tanggal_bayar;
                $metode       = $iuran->metodePembayaran->nama ?? '-';
            } else {
                $status       = 'belum';
                $jumlah       = $siswa->kelas->biaya_bulanan ?? 0;
                $tanggalBayar = null;
                $metode       = '-';
            }

            if ($status === 'lunas') {
                $totalLunas++;
                $totalTerkumpul += $jumlah;
            } else {
                $totalBelumLunas++;
                $totalOutstanding += $jumlah;
            }

            $data[] = [
                'siswa'         => $siswa,
                'kelas'         => $siswa->kelas,
                'status'        => $status,
                'jumlah'        => $jumlah,
                'tanggal_bayar' => $tanggalBayar,
                'metode'        => $metode,
            ];
        }

        $totalSiswa = count($data);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $kelasList   = Kelas::orderBy('nama')->get();
        $namaKlub    = config('app.name', 'Klub Renang');
        $tanggalCetak = now()->toDateString();

        $viewData = compact(
            'bulan',
            'tahun',
            'kelasId',
            'data',
            'totalSiswa',
            'totalLunas',
            'totalBelumLunas',
            'totalTerkumpul',
            'totalOutstanding',
            'kelasList',
            'namaBulan',
            'namaKlub',
            'tanggalCetak'
        );

        $filename = 'rekap-iuran-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . $tahun . '.pdf';

        return Pdf::loadView('admin.laporan.pdf.iuran-rutin', $viewData)
            ->setPaper('a4', 'portrait')
            ->download($filename);
    }

    /**
     * Export rekap kehadiran ke PDF
     */
    public function kehadiranPdf(Request $request)
    {
        $tanggalDari = $request->filled('tanggal_dari')
            ? Carbon::parse($request->tanggal_dari)
            : Carbon::now()->subMonth();

        $tanggalSampai = $request->filled('tanggal_sampai')
            ? Carbon::parse($request->tanggal_sampai)
            : Carbon::now();

        $query = Siswa::with(['kelas', 'kehadiran.sesi']);

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $siswaList = $query->where('status', 'aktif')->get();

        $attendanceData = $siswaList->map(function ($siswa) use ($tanggalDari, $tanggalSampai) {
            $kehadiranInRange = $siswa->kehadiran()
                ->whereHas('sesi', function ($q) use ($tanggalDari, $tanggalSampai) {
                    $q->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
                })
                ->get();

            $totalSesi = $kehadiranInRange->count();
            $hadir     = $kehadiranInRange->where('status', 'hadir')->count();
            $izin      = $kehadiranInRange->where('status', 'izin')->count();
            $sakit     = $kehadiranInRange->where('status', 'sakit')->count();
            $alpha     = $kehadiranInRange->where('status', 'alpha')->count();
            $persentase = $totalSesi > 0 ? ($hadir / $totalSesi) * 100 : 0;

            return [
                'siswa'      => $siswa,
                'total_sesi' => $totalSesi,
                'hadir'      => $hadir,
                'izin'       => $izin,
                'sakit'      => $sakit,
                'alpha'      => $alpha,
                'persentase' => round($persentase, 1),
            ];
        })->sortByDesc('persentase')->values();

        $namaKlub    = config('app.name', 'Klub Renang');
        $tanggalCetak = now()->toDateString();

        $viewData = compact(
            'attendanceData',
            'tanggalDari',
            'tanggalSampai',
            'namaKlub',
            'tanggalCetak'
        );

        $filename = 'rekap-kehadiran-' . $tanggalDari->format('Y-m-d') . '-sd-' . $tanggalSampai->format('Y-m-d') . '.pdf';

        return Pdf::loadView('admin.laporan.pdf.kehadiran', $viewData)
            ->setPaper('a4', 'landscape')
            ->download($filename);
    }
}
