<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\ItemKas;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pengeluaran::with(['itemKas', 'dibuatOleh']);
        
        // Filter by date range
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }
        
        // Filter by item_kas_id
        if ($request->filled('item_kas_id')) {
            $query->where('item_kas_id', $request->item_kas_id);
        }
        
        $pengeluaran = $query->orderBy('tanggal', 'desc')
                            ->paginate(20);
        
        $itemKasList = ItemKas::orderBy('nama')->get();
        
        // Calculate totals
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        
        // Calculate total for filtered results
        $totalFiltered = $query->sum('jumlah');
        
        // Calculate total by category
        $totalByCategory = Pengeluaran::selectRaw('item_kas_id, SUM(jumlah) as total')
                                     ->groupBy('item_kas_id')
                                     ->with('itemKas')
                                     ->get();
        
        return view('admin.pengeluaran.index', compact('pengeluaran', 'itemKasList', 'totalPengeluaran', 'totalFiltered', 'totalByCategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $itemKasList = ItemKas::orderBy('nama')->get();
        
        return view('admin.pengeluaran.create', compact('itemKasList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_kas_id' => 'nullable|exists:item_kas,id',
            'nama_pengeluaran' => 'required|string|max:150',
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date|before_or_equal:today',
            'keterangan' => 'nullable|string',
        ], [
            'nama_pengeluaran.required' => 'Nama pengeluaran wajib diisi',
            'nama_pengeluaran.max' => 'Nama pengeluaran maksimal 150 karakter',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah harus lebih dari 0',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
        ]);
        
        // Add dibuat_oleh
        $validated['dibuat_oleh'] = auth()->id();
        
        Pengeluaran::create($validated);
        
        return redirect()->route('admin.pengeluaran.index')
                        ->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengeluaran $pengeluaran)
    {
        $pengeluaran->load(['itemKas', 'dibuatOleh']);
        
        return view('admin.pengeluaran.show', compact('pengeluaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengeluaran $pengeluaran)
    {
        $itemKasList = ItemKas::orderBy('nama')->get();
        
        return view('admin.pengeluaran.edit', compact('pengeluaran', 'itemKasList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'item_kas_id' => 'nullable|exists:item_kas,id',
            'nama_pengeluaran' => 'required|string|max:150',
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date|before_or_equal:today',
            'keterangan' => 'nullable|string',
        ], [
            'nama_pengeluaran.required' => 'Nama pengeluaran wajib diisi',
            'nama_pengeluaran.max' => 'Nama pengeluaran maksimal 150 karakter',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah harus lebih dari 0',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
        ]);
        
        $pengeluaran->update($validated);
        
        return redirect()->route('admin.pengeluaran.index')
                        ->with('success', 'Pengeluaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        
        return redirect()->route('admin.pengeluaran.index')
                        ->with('success', 'Pengeluaran berhasil dihapus');
    }
}
