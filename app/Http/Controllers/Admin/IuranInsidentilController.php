<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IuranInsidentil;
use App\Models\Siswa;
use App\Models\ItemKas;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IuranInsidentilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = IuranInsidentil::with(['siswa.kelas', 'itemKas', 'metodePembayaran']);
        
        // Filter by siswa_id
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter by status
        if ($request->filled('status_bayar')) {
            $query->where('status_bayar', $request->status_bayar);
        }
        
        $iuranInsidentil = $query->orderBy('tanggal', 'desc')->paginate(20);
        
        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        
        // Calculate totals
        $totalLunas = IuranInsidentil::where('status_bayar', 'lunas')->sum('jumlah');
        $totalBelum = IuranInsidentil::where('status_bayar', 'belum')->sum('jumlah');
        
        return view('admin.iuran-insidentil.index', compact('iuranInsidentil', 'siswaList', 'totalLunas', 'totalBelum'));
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
        
        $itemKasList = ItemKas::orderBy('nama')->get();
        $metodePembayaran = MetodePembayaran::orderBy('nama')->get();
        
        return view('admin.iuran-insidentil.create', compact('siswaList', 'itemKasList', 'metodePembayaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'item_kas_id' => 'nullable|exists:item_kas,id',
            'nama_item' => 'required|string|max:150',
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date|before_or_equal:today',
            'status_bayar' => 'required|in:lunas,belum',
            'tanggal_bayar' => 'nullable|required_if:status_bayar,lunas|date',
            'metode_pembayaran_id' => 'nullable|required_if:status_bayar,lunas|exists:metode_pembayaran,id',
            'catatan' => 'nullable|string|max:255',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'nama_item.required' => 'Nama item wajib diisi',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah harus lebih dari 0',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'status_bayar.required' => 'Status bayar wajib dipilih',
            'tanggal_bayar.required_if' => 'Tanggal bayar wajib diisi jika status lunas',
            'metode_pembayaran_id.required_if' => 'Metode pembayaran wajib dipilih jika status lunas',
        ]);
        
        $validated['dibuat_oleh'] = Auth::id();
        
        IuranInsidentil::create($validated);
        
        return redirect()->route('admin.iuran-insidentil.index')
                        ->with('success', 'Iuran insidentil berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(IuranInsidentil $iuranInsidentil)
    {
        $iuranInsidentil->load(['siswa.kelas', 'itemKas', 'metodePembayaran', 'dibuatOleh']);
        
        return view('admin.iuran-insidentil.show', compact('iuranInsidentil'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IuranInsidentil $iuranInsidentil)
    {
        $siswaList = Siswa::where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();
        
        $itemKasList = ItemKas::orderBy('nama')->get();
        $metodePembayaran = MetodePembayaran::orderBy('nama')->get();
        
        return view('admin.iuran-insidentil.edit', compact('iuranInsidentil', 'siswaList', 'itemKasList', 'metodePembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IuranInsidentil $iuranInsidentil)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'item_kas_id' => 'nullable|exists:item_kas,id',
            'nama_item' => 'required|string|max:150',
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date|before_or_equal:today',
            'status_bayar' => 'required|in:lunas,belum',
            'tanggal_bayar' => 'nullable|required_if:status_bayar,lunas|date',
            'metode_pembayaran_id' => 'nullable|required_if:status_bayar,lunas|exists:metode_pembayaran,id',
            'catatan' => 'nullable|string|max:255',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'nama_item.required' => 'Nama item wajib diisi',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah harus lebih dari 0',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'status_bayar.required' => 'Status bayar wajib dipilih',
            'tanggal_bayar.required_if' => 'Tanggal bayar wajib diisi jika status lunas',
            'metode_pembayaran_id.required_if' => 'Metode pembayaran wajib dipilih jika status lunas',
        ]);
        
        $iuranInsidentil->update($validated);
        
        return redirect()->route('admin.iuran-insidentil.index')
                        ->with('success', 'Iuran insidentil berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IuranInsidentil $iuranInsidentil)
    {
        // Only allow delete if status is belum
        if ($iuranInsidentil->status_bayar === 'lunas') {
            return redirect()->route('admin.iuran-insidentil.index')
                            ->with('error', 'Tidak dapat menghapus iuran yang sudah lunas');
        }
        
        $iuranInsidentil->delete();
        
        return redirect()->route('admin.iuran-insidentil.index')
                        ->with('success', 'Iuran insidentil berhasil dihapus');
    }
}
