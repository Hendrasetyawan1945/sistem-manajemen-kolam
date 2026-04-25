<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\IuranRutin;
use App\Models\IuranInsidentil;
use App\Models\IuranKejuaraan;
use App\Models\Angsuran;
use App\Models\Pengeluaran;
use App\Models\Kehadiran;
use App\Models\Sesi;
use App\Models\Rapor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialReportExport;
use App\Exports\TuitionSummaryExport;
use App\Exports\AttendanceReportExport;
use App\Exports\StudentReportCardExport;
use App\Exports\StudentListExport;

class ExportController extends Controller
{
    /**
     * Export Financial Report to PDF
     */
    public function exportFinancialReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Calculate income
        $iuranRutin = IuranRutin::where('status_bayar', 'lunas')
                               ->whereBetween('tanggal_bayar', [$startDate, $endDate])
                               ->with('siswa')
                               ->get();

        $iuranInsidentil = IuranInsidentil::where('status_bayar', 'lunas')
                                        ->whereBetween('tanggal_bayar', [$startDate, $endDate])
                                        ->with('siswa')
                                        ->get();

        $iuranKejuaraan = IuranKejuaraan::where('status_bayar', 'lunas')
                                       ->whereBetween('tanggal_bayar', [$startDate, $endDate])
                                       ->with(['siswa', 'kejuaraan'])
                                       ->get();

        // Calculate expenses
        $pengeluaran = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])
                                 ->with(['itemKas', 'metodePembayaran'])
                                 ->get();

        // Calculate totals
        $totalIuranRutin = $iuranRutin->sum('jumlah');
        $totalIuranInsidentil = $iuranInsidentil->sum('jumlah');
        $totalIuranKejuaraan = $iuranKejuaraan->sum('jumlah');
        $totalIncome = $totalIuranRutin + $totalIuranInsidentil + $totalIuranKejuaraan;
        
        $totalExpenses = $pengeluaran->sum('jumlah');
        $netBalance = $totalIncome - $totalExpenses;

        // Income breakdown by category
        $incomeBreakdown = [
            'Iuran Rutin' => $totalIuranRutin,
            'Iuran Insidentil' => $totalIuranInsidentil,
            'Iuran Kejuaraan' => $totalIuranKejuaraan,
        ];

        // Expense breakdown by category
        $expenseBreakdown = $pengeluaran->groupBy('itemKas.nama')
                                      ->map(function ($items) {
                                          return $items->sum('jumlah');
                                      });

        $data = [
            'title' => 'Laporan Keuangan',
            'period' => formatTanggal($startDate) . ' - ' . formatTanggal($endDate),
            'generated_at' => formatTanggal(now()),
            'iuranRutin' => $iuranRutin,
            'iuranInsidentil' => $iuranInsidentil,
            'iuranKejuaraan' => $iuranKejuaraan,
            'pengeluaran' => $pengeluaran,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'netBalance' => $netBalance,
            'incomeBreakdown' => $incomeBreakdown,
            'expenseBreakdown' => $expenseBreakdown,
        ];

        $pdf = Pdf::loadView('admin.exports.financial-report', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-keuangan-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export Financial Report to Excel
     */
    public function exportFinancialReportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $filename = 'laporan-keuangan-' . $startDate . '-' . $endDate . '.xlsx';
        
        return Excel::download(new FinancialReportExport($startDate, $endDate), $filename);
    }

    /**
     * Export Tuition Summary Report to Excel
     */
    public function exportTuitionSummaryExcel(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2030',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kelasId = $request->kelas_id;

        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $filename = 'laporan-iuran-' . $bulanNama[$bulan] . '-' . $tahun . '.xlsx';
        
        return Excel::download(new TuitionSummaryExport($bulan, $tahun, $kelasId), $filename);
    }

    /**
     * Export Attendance Report to Excel
     */
    public function exportAttendanceReportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'kelas_id' => 'nullable|exists:kelas,id',
            'min_attendance' => 'nullable|integer|min:0|max:100',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $kelasId = $request->kelas_id;
        $minAttendance = $request->min_attendance ?? 0;
        
        $filename = 'laporan-kehadiran-' . $startDate . '-' . $endDate . '.xlsx';
        
        return Excel::download(new AttendanceReportExport($startDate, $endDate, $kelasId, $minAttendance), $filename);
    }

    /**
     * Export Student Report Card to Excel
     */
    public function exportStudentReportCardExcel(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'periode' => 'nullable|string',
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);
        $periode = $request->periode;
        
        $filename = 'rapor-' . str_replace(' ', '-', strtolower($siswa->nama)) . '.xlsx';
        
        return Excel::download(new StudentReportCardExport($request->siswa_id, $periode), $filename);
    }

    /**
     * Export Student List to Excel
     */
    public function exportStudentListExcel(Request $request)
    {
        $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'status' => 'nullable|in:aktif,cuti,nonaktif',
        ]);

        $kelasId = $request->kelas_id;
        $status = $request->status;
        
        $filename = 'daftar-siswa-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new StudentListExport($kelasId, $status), $filename);
    }

    /**
     * Export Tuition Summary Report to PDF
     */
    public function exportTuitionSummary(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2030',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kelasId = $request->kelas_id;

        // Get students
        $siswaQuery = Siswa::with(['kelas', 'user']);
        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }
        $siswaList = $siswaQuery->where('status', 'aktif')->get();

        // Get tuition data
        $tuitionData = [];
        $totalLunas = 0;
        $totalBelum = 0;
        $totalCollected = 0;
        $totalOutstanding = 0;

        foreach ($siswaList as $siswa) {
            $iuran = IuranRutin::where('siswa_id', $siswa->id)
                              ->where('bulan', $bulan)
                              ->where('tahun', $tahun)
                              ->first();

            $status = $iuran ? $iuran->status_bayar : 'belum';
            $jumlah = $iuran ? $iuran->jumlah : ($siswa->kelas->biaya_bulanan ?? 0);
            $tanggalBayar = $iuran && $iuran->status_bayar === 'lunas' ? $iuran->tanggal_bayar : null;

            $tuitionData[] = [
                'siswa' => $siswa,
                'status' => $status,
                'jumlah' => $jumlah,
                'tanggal_bayar' => $tanggalBayar,
            ];

            if ($status === 'lunas') {
                $totalLunas++;
                $totalCollected += $jumlah;
            } else {
                $totalBelum++;
                $totalOutstanding += $jumlah;
            }
        }

        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $kelas = $kelasId ? Kelas::find($kelasId) : null;

        $data = [
            'title' => 'Laporan Iuran Bulanan',
            'period' => $bulanNama[$bulan] . ' ' . $tahun,
            'kelas' => $kelas,
            'generated_at' => formatTanggal(now()),
            'tuitionData' => $tuitionData,
            'totalLunas' => $totalLunas,
            'totalBelum' => $totalBelum,
            'totalCollected' => $totalCollected,
            'totalOutstanding' => $totalOutstanding,
            'totalSiswa' => count($tuitionData),
        ];

        $pdf = Pdf::loadView('admin.exports.tuition-summary', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'laporan-iuran-' . $bulanNama[$bulan] . '-' . $tahun . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export Attendance Report to PDF
     */
    public function exportAttendanceReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'kelas_id' => 'nullable|exists:kelas,id',
            'min_attendance' => 'nullable|integer|min:0|max:100',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $kelasId = $request->kelas_id;
        $minAttendance = $request->min_attendance ?? 0;

        // Get sessions in date range
        $sesiQuery = Sesi::with(['kelas', 'kehadiran.siswa'])
                        ->whereBetween('tanggal', [$startDate, $endDate]);
        
        if ($kelasId) {
            $sesiQuery->where('kelas_id', $kelasId);
        }
        
        $sesiList = $sesiQuery->orderBy('tanggal')->get();

        // Calculate attendance per student
        $attendanceData = [];
        $siswaAttendance = [];

        foreach ($sesiList as $sesi) {
            foreach ($sesi->kehadiran as $kehadiran) {
                $siswaId = $kehadiran->siswa_id;
                
                if (!isset($siswaAttendance[$siswaId])) {
                    $siswaAttendance[$siswaId] = [
                        'siswa' => $kehadiran->siswa,
                        'total_sesi' => 0,
                        'hadir' => 0,
                        'alpha' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                    ];
                }
                
                $siswaAttendance[$siswaId]['total_sesi']++;
                $siswaAttendance[$siswaId][$kehadiran->status]++;
            }
        }

        // Calculate percentages and filter by minimum attendance
        foreach ($siswaAttendance as $siswaId => $data) {
            $percentage = $data['total_sesi'] > 0 ? ($data['hadir'] / $data['total_sesi']) * 100 : 0;
            
            if ($percentage >= $minAttendance) {
                $attendanceData[] = array_merge($data, [
                    'percentage' => round($percentage, 1)
                ]);
            }
        }

        // Sort by attendance percentage descending
        usort($attendanceData, function($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        $kelas = $kelasId ? Kelas::find($kelasId) : null;

        $data = [
            'title' => 'Laporan Kehadiran',
            'period' => formatTanggal($startDate) . ' - ' . formatTanggal($endDate),
            'kelas' => $kelas,
            'min_attendance' => $minAttendance,
            'generated_at' => formatTanggal(now()),
            'attendanceData' => $attendanceData,
            'totalSesi' => $sesiList->count(),
            'totalSiswa' => count($attendanceData),
        ];

        $pdf = Pdf::loadView('admin.exports.attendance-report', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'laporan-kehadiran-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export Student Report Card to PDF
     */
    public function exportStudentReportCard(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'periode' => 'nullable|string',
        ]);

        $siswa = Siswa::with(['kelas', 'user'])->findOrFail($request->siswa_id);
        $periode = $request->periode;

        // Get rapor data
        $raporQuery = Rapor::where('siswa_id', $siswa->id)->with('coach');
        
        if ($periode) {
            $raporQuery->where('periode', $periode);
        }
        
        $raporList = $raporQuery->orderBy('periode', 'desc')->get();

        // Get personal best records
        $personalBest = $siswa->personalBest()->orderBy('gaya_renang')->orderBy('jarak')->get();

        // Get recent competition results
        $catatanWaktu = $siswa->catatanWaktu()
                             ->with('kejuaraan')
                             ->orderBy('created_at', 'desc')
                             ->limit(10)
                             ->get();

        $data = [
            'title' => 'Rapor Siswa',
            'siswa' => $siswa,
            'periode' => $periode,
            'generated_at' => formatTanggal(now()),
            'raporList' => $raporList,
            'personalBest' => $personalBest,
            'catatanWaktu' => $catatanWaktu,
        ];

        $pdf = Pdf::loadView('admin.exports.student-report-card', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'rapor-' . str_replace(' ', '-', strtolower($siswa->nama)) . '.pdf';
        
        return $pdf->download($filename);
    }
}