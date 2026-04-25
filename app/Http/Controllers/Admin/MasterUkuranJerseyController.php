<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterUkuranJersey;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterUkuranJerseyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ukuranList = MasterUkuranJersey::withCount('jersey as total_jersey')
            ->orderBy('ukuran')
            ->paginate(20);

        return view('admin.master-ukuran-jersey.index', compact('ukuranList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-ukuran-jersey.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ukuran'     => 'required|string|max:10|unique:master_ukuran_jersey,ukuran',
            'keterangan' => 'nullable|string|max:100',
        ], [
            'ukuran.required' => 'Ukuran jersey wajib diisi',
            'ukuran.max'      => 'Ukuran jersey maksimal 10 karakter',
            'ukuran.unique'   => 'Ukuran jersey sudah terdaftar',
        ]);

        MasterUkuranJersey::create($validated);

        return redirect()->route('admin.master-ukuran-jersey.index')
            ->with('success', 'Ukuran jersey berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterUkuranJersey $masterUkuranJersey)
    {
        return view('admin.master-ukuran-jersey.edit', compact('masterUkuranJersey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterUkuranJersey $masterUkuranJersey)
    {
        $validated = $request->validate([
            'ukuran'     => ['required', 'string', 'max:10', Rule::unique('master_ukuran_jersey', 'ukuran')->ignore($masterUkuranJersey->id)],
            'keterangan' => 'nullable|string|max:100',
        ], [
            'ukuran.required' => 'Ukuran jersey wajib diisi',
            'ukuran.max'      => 'Ukuran jersey maksimal 10 karakter',
            'ukuran.unique'   => 'Ukuran jersey sudah terdaftar',
        ]);

        $masterUkuranJersey->update($validated);

        return redirect()->route('admin.master-ukuran-jersey.index')
            ->with('success', 'Ukuran jersey berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterUkuranJersey $masterUkuranJersey)
    {
        // Prevent delete if there are jersey orders related
        if ($masterUkuranJersey->jersey()->count() > 0) {
            return redirect()->route('admin.master-ukuran-jersey.index')
                ->with('error', 'Tidak dapat menghapus ukuran jersey yang masih digunakan pada data jersey. Hapus data jersey terkait terlebih dahulu.');
        }

        $masterUkuranJersey->delete();

        return redirect()->route('admin.master-ukuran-jersey.index')
            ->with('success', 'Ukuran jersey berhasil dihapus');
    }
}
