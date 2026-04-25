<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IuranRutin;
use App\Models\Siswa;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IuranRutinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = IuranRutin::with(['siswa.kelas', 'metodePembayaran']);
        
        // Filter by siswa_id
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter by bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        
        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        
        // Filter by status
        if ($request->filled('status_bayar')) {
            $query->where('status_bayar', $request->status_bayar);
        }
        
        $iuranRutin = $query->orderBy('tahun', 'desc')
                           ->orderBy('bulan', 'desc')
                           ->paginate(20);
        
        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        
        // Calculate totals
        $totalLunas = IuranRutin::where('status_bayar', 'lunas')->sum('jumlah');
        $totalBelum = IuranRutin::where('status_bayar', 'belum')->sum('jumlah');
        
        return view('admin.iuran-rutin.index', compact('iuranRutin', 'siswaList', 'totalLunas', 'totalBelum'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswaList = Siswa::where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();
        
        $metodePembayaran = MetodePembayaran::orderBy('nama')->get();
        
        return view('admin.iuran-rutin.create', compact('siswaList', 'metodePembayaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|digits:4|min:2020|max:2100',
            'jumlah' => 'required|numeric|min:0.01',
            'status_bayar' => 'required|in:lunas,belum',
            'tanggal_bayar' => 'nullable|required_if:status_bayar,lunas|date',
            'metode_pembayaran_id' => 'nullable|required_if:status_bayar,lunas|exists:metode_pembayaran,id',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'bulan.required' => 'Bulan wajib diisi',
            'bulan.min' => 'Bulan harus antara 1-12',
            'bulan.max' => 'Bulan harus antara 1-12',
            'tahun.required' => 'Tahun wajib diisi',
            'tahun.digits' => 'Tahun harus 4 digit',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah harus lebih dari 0',
            'status_bayar.required' => 'Status bayar wajib dipilih',
            'tanggal_bayar.required_if' => 'Tanggal bayar wajib diisi jika status lunas',
            'metode_pembayaran_id.required_if' => 'Metode pembayaran wajib dipilih jika status lunas',
        ]);
        
        // Check for duplicate
        $exists = IuranRutin::where('siswa_id', $validated['siswa_id'])
                           ->where('bulan', $validated['bulan'])
                           ->where('tahun', $validated['tahun'])
                           ->exists();
        
        if ($exists) {
            return back()->withInput()
                        ->with('error', 'Iuran untuk siswa ini pada bulan dan tahun tersebut sudah ada');
        }
        
        IuranRutin::create(array_merge($validated, ['dibuat_oleh' => auth()->id()]));
        
        return redirect()->route('admin.iuran-rutin.index')
                        ->with('success', 'Iuran rutin berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(IuranRutin $iuranRutin)
    {
        $iuranRutin->load(['siswa.kelas', 'metodePembayaran']);
        
        return view('admin.iuran-rutin.show', compact('iuranRutin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IuranRutin $iuranRutin)
    {
        $siswaList = Siswa::where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();
        
        $metodePembayaran = MetodePembayaran::orderBy('nama')->get();
        
        return view('admin.iuran-rutin.edit', compact('iuranRutin', 'siswaList', 'metodePembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IuranRutin $iuranRutin)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|digits:4|min:2020|max:2100',
            'jumlah' => 'required|numeric|min:0.01',
            'status_bayar' => 'required|in:lunas,belum',
            'tanggal_bayar' => 'nullable|required_if:status_bayar,lunas|date',
            'metode_pembayaran_id' => 'nullable|required_if:status_bayar,lunas|exists:metode_pembayaran,id',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'bulan.required' => 'Bulan wajib diisi',
            'bulan.min' => 'Bulan harus antara 1-12',
            'bulan.max' => 'Bulan harus antara 1-12',
            'tahun.required' => 'Tahun wajib diisi',
            'tahun.digits' => 'Tahun harus 4 digit',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah harus lebih dari 0',
            'status_bayar.required' => 'Status bayar wajib dipilih',
            'tanggal_bayar.required_if' => 'Tanggal bayar wajib diisi jika status lunas',
            'metode_pembayaran_id.required_if' => 'Metode pembayaran wajib dipilih jika status lunas',
        ]);
        
        // Check for duplicate (excluding current record)
        $exists = IuranRutin::where('siswa_id', $validated['siswa_id'])
                           ->where('bulan', $validated['bulan'])
                           ->where('tahun', $validated['tahun'])
                           ->where('id', '!=', $iuranRutin->id)
                           ->exists();
        
        if ($exists) {
            return back()->withInput()
                        ->with('error', 'Iuran untuk siswa ini pada bulan dan tahun tersebut sudah ada');
        }
        
        $iuranRutin->update($validated);
        
        return redirect()->route('admin.iuran-rutin.index')
                        ->with('success', 'Iuran rutin berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IuranRutin $iuranRutin)
    {
        $iuranRutin->delete();
        
        return redirect()->route('admin.iuran-rutin.index')
                        ->with('success', 'Iuran rutin berhasil dihapus');
    }
}
