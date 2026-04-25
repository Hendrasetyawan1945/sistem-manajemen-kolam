<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IuranKejuaraan;
use App\Models\Kejuaraan;
use App\Models\Siswa;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IuranKejuaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = IuranKejuaraan::with(['kejuaraan', 'siswa.kelas', 'metodePembayaran']);
        
        // Filter by kejuaraan_id
        if ($request->filled('kejuaraan_id')) {
            $query->where('kejuaraan_id', $request->kejuaraan_id);
        }
        
        // Filter by siswa_id
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter by status
        if ($request->filled('status_bayar')) {
            $query->where('status_bayar', $request->status_bayar);
        }
        
        $iuranKejuaraan = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $kejuaraanList = Kejuaraan::orderBy('tanggal_mulai', 'desc')->get();
        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        
        return view('admin.iuran-kejuaraan.index', compact('iuranKejuaraan', 'kejuaraanList', 'siswaList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $kejuaraanList = Kejuaraan::orderBy('tanggal_mulai', 'desc')->get();
        $siswaList = Siswa::where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();
        
        $metodePembayaran = MetodePembayaran::orderBy('nama')->get();
        
        $selectedKejuaraanId = $request->get('kejuaraan_id');
        
        return view('admin.iuran-kejuaraan.create', compact('kejuaraanList', 'siswaList', 'metodePembayaran', 'selectedKejuaraanId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kejuaraan_id' => 'required|exists:kejuaraan,id',
            'siswa_id' => 'required|exists:siswa,id',
            'jumlah' => 'required|numeric|min:0.01',
            'status_bayar' => 'required|in:lunas,belum',
            'tanggal_bayar' => 'nullable|required_if:status_bayar,lunas|date',
            'metode_pembayaran_id' => 'nullable|required_if:status_bayar,lunas|exists:metode_pembayaran,id',
        ], [
            'kejuaraan_id.required' => 'Kejuaraan wajib dipilih',
            'siswa_id.required' => 'Siswa wajib dipilih',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah harus lebih dari 0',
            'status_bayar.required' => 'Status bayar wajib dipilih',
            'tanggal_bayar.required_if' => 'Tanggal bayar wajib diisi jika status lunas',
            'metode_pembayaran_id.required_if' => 'Metode pembayaran wajib dipilih jika status lunas',
        ]);
        
        // Check for duplicate
        $exists = IuranKejuaraan::where('kejuaraan_id', $validated['kejuaraan_id'])
                               ->where('siswa_id', $validated['siswa_id'])
                               ->exists();
        
        if ($exists) {
            return back()->withInput()
                        ->with('error', 'Siswa ini sudah terdaftar di kejuaraan tersebut');
        }
        
        $validated['dibuat_oleh'] = Auth::id();
        
        IuranKejuaraan::create($validated);
        
        return redirect()->route('admin.iuran-kejuaraan.index')
                        ->with('success', 'Pendaftaran kejuaraan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(IuranKejuaraan $iuranKejuaraan)
    {
        $iuranKejuaraan->load(['kejuaraan', 'siswa.kelas', 'metodePembayaran', 'dibuatOleh']);
        
        return view('admin.iuran-kejuaraan.show', compact('iuranKejuaraan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IuranKejuaraan $iuranKejuaraan)
    {
        $kejuaraanList = Kejuaraan::orderBy('tanggal_mulai', 'desc')->get();
        $siswaList = Siswa::where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();
        
        $metodePembayaran = MetodePembayaran::orderBy('nama')->get();
        
        return view('admin.iuran-kejuaraan.edit', compact('iuranKejuaraan', 'kejuaraanList', 'siswaList', 'metodePembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IuranKejuaraan $iuranKejuaraan)
    {
        $validated = $request->validate([
            'kejuaraan_id' => 'required|exists:kejuaraan,id',
            'siswa_id' => 'required|exists:siswa,id',
            'jumlah' => 'required|numeric|min:0.01',
            'status_bayar' => 'required|in:lunas,belum',
            'tanggal_bayar' => 'nullable|required_if:status_bayar,lunas|date',
            'metode_pembayaran_id' => 'nullable|required_if:status_bayar,lunas|exists:metode_pembayaran,id',
        ], [
            'kejuaraan_id.required' => 'Kejuaraan wajib dipilih',
            'siswa_id.required' => 'Siswa wajib dipilih',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah harus lebih dari 0',
            'status_bayar.required' => 'Status bayar wajib dipilih',
            'tanggal_bayar.required_if' => 'Tanggal bayar wajib diisi jika status lunas',
            'metode_pembayaran_id.required_if' => 'Metode pembayaran wajib dipilih jika status lunas',
        ]);
        
        // Check for duplicate (excluding current record)
        $exists = IuranKejuaraan::where('kejuaraan_id', $validated['kejuaraan_id'])
                               ->where('siswa_id', $validated['siswa_id'])
                               ->where('id', '!=', $iuranKejuaraan->id)
                               ->exists();
        
        if ($exists) {
            return back()->withInput()
                        ->with('error', 'Siswa ini sudah terdaftar di kejuaraan tersebut');
        }
        
        $iuranKejuaraan->update($validated);
        
        return redirect()->route('admin.iuran-kejuaraan.index')
                        ->with('success', 'Pendaftaran kejuaraan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IuranKejuaraan $iuranKejuaraan)
    {
        $iuranKejuaraan->delete();
        
        return redirect()->route('admin.iuran-kejuaraan.index')
                        ->with('success', 'Pendaftaran kejuaraan berhasil dihapus');
    }
}
