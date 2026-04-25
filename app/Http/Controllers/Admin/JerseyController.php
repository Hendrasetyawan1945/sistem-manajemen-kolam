<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jersey;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\MasterUkuranJersey;
use Illuminate\Http\Request;

class JerseyController extends Controller
{
    public function index(Request $request)
    {
        $query = Jersey::with(['siswa.kelas', 'masterUkuranJersey']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        $jerseys = $query->orderBy('tanggal_pesan', 'desc')->paginate(20);
        $siswaList = Siswa::orderBy('nama')->get();
        $statsBySize = MasterUkuranJersey::withCount(['jerseys as total_pesanan'])->orderBy('ukuran')->get();

        $siswaWithJersey = Jersey::pluck('siswa_id')->unique();
        $siswaWithoutJersey = Siswa::where('status', 'aktif')
            ->whereNotIn('id', $siswaWithJersey)
            ->with('kelas')
            ->orderBy('nama')
            ->get();

        return view('admin.jersey.index', compact(
            'jerseys', 'siswaList', 'statsBySize', 'siswaWithoutJersey'
        ));
    }

    public function create()
    {
        // Hanya siswa yang belum punya pesanan jersey
        $siswaWithJersey = Jersey::pluck('siswa_id')->unique();
        $siswaList = Siswa::where('status', 'aktif')
            ->whereNotIn('id', $siswaWithJersey)
            ->with('kelas')
            ->orderBy('nama')
            ->get();
        $ukuranList = MasterUkuranJersey::orderBy('ukuran')->get();

        return view('admin.jersey.create', compact('siswaList', 'ukuranList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id'                => 'required|exists:siswa,id|unique:jersey,siswa_id',
            'master_ukuran_jersey_id' => 'required|exists:master_ukuran_jersey,id',
            'tanggal_pesan'           => 'required|date|before_or_equal:today',
            'catatan'                 => 'nullable|string|max:255',
        ], [
            'siswa_id.required'                => 'Siswa wajib dipilih',
            'siswa_id.unique'                  => 'Siswa ini sudah memiliki pesanan jersey',
            'master_ukuran_jersey_id.required' => 'Ukuran jersey wajib dipilih',
            'tanggal_pesan.required'           => 'Tanggal pesan wajib diisi',
            'tanggal_pesan.before_or_equal'    => 'Tanggal pesan tidak boleh lebih dari hari ini',
        ]);

        $validated['status'] = 'dipesan';

        Jersey::create($validated);

        return redirect()->route('admin.jersey.index')
            ->with('success', 'Pesanan jersey berhasil dibuat dengan status Dipesan');
    }

    public function show(Jersey $jersey)
    {
        $jersey->load(['siswa.kelas', 'masterUkuranJersey']);
        $ukuranList = MasterUkuranJersey::orderBy('ukuran')->get();

        return view('admin.jersey.show', compact('jersey', 'ukuranList'));
    }

    public function edit(Jersey $jersey)
    {
        $ukuranList = MasterUkuranJersey::orderBy('ukuran')->get();

        return view('admin.jersey.edit', compact('jersey', 'ukuranList'));
    }

    public function update(Request $request, Jersey $jersey)
    {
        $validated = $request->validate([
            'master_ukuran_jersey_id' => 'required|exists:master_ukuran_jersey,id',
            'tanggal_pesan'           => 'required|date|before_or_equal:today',
            'catatan'                 => 'nullable|string|max:255',
        ], [
            'master_ukuran_jersey_id.required' => 'Ukuran jersey wajib dipilih',
            'tanggal_pesan.required'           => 'Tanggal pesan wajib diisi',
            'tanggal_pesan.before_or_equal'    => 'Tanggal pesan tidak boleh lebih dari hari ini',
        ]);

        $jersey->update($validated);

        return redirect()->route('admin.jersey.show', $jersey->id)
            ->with('success', 'Data pesanan jersey berhasil diperbarui');
    }

    public function destroy(Jersey $jersey)
    {
        // Hanya bisa hapus jika status dipesan atau dibatalkan
        if ($jersey->status === 'diterima') {
            return back()->with('error', 'Jersey yang sudah diterima tidak dapat dihapus');
        }

        $jersey->delete();

        return redirect()->route('admin.jersey.index')
            ->with('success', 'Pesanan jersey berhasil dihapus');
    }

    public function updateStatus(Request $request, Jersey $jersey)
    {
        $validated = $request->validate([
            'status' => 'required|in:dipesan,diterima,dibatalkan',
        ], [
            'status.required' => 'Status wajib dipilih',
            'status.in'       => 'Status tidak valid',
        ]);

        // Validasi alur status: dipesan → diterima atau dibatalkan
        // Tidak bisa kembali dari diterima ke dipesan
        if ($jersey->status === 'diterima' && $validated['status'] === 'dipesan') {
            return back()->with('error', 'Jersey yang sudah diterima tidak bisa dikembalikan ke status Dipesan');
        }

        $jersey->update(['status' => $validated['status']]);

        $label = ['dipesan' => 'Dipesan', 'diterima' => 'Diterima', 'dibatalkan' => 'Dibatalkan'];

        return back()->with('success', 'Status jersey berhasil diubah menjadi ' . $label[$validated['status']]);
    }

    public function report(Request $request)
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $kelasId = $request->input('kelas_id');

        $totalPesanan    = Jersey::count();
        $totalDiterima   = Jersey::where('status', 'diterima')->count();
        $totalDipesan    = Jersey::where('status', 'dipesan')->count();
        $totalDibatalkan = Jersey::where('status', 'dibatalkan')->count();

        $ukuranList = MasterUkuranJersey::orderBy('ukuran')->get();
        $bySize = $ukuranList->map(function ($ukuran) {
            $q = Jersey::where('master_ukuran_jersey_id', $ukuran->id);
            return [
                'ukuran'     => $ukuran->ukuran,
                'keterangan' => $ukuran->keterangan,
                'total'      => (clone $q)->count(),
                'diterima'   => (clone $q)->where('status', 'diterima')->count(),
                'dipesan'    => (clone $q)->where('status', 'dipesan')->count(),
                'dibatalkan' => (clone $q)->where('status', 'dibatalkan')->count(),
            ];
        });

        $siswaWithJersey = Jersey::pluck('siswa_id')->unique();
        $siswaQuery = Siswa::where('status', 'aktif')
            ->whereNotIn('id', $siswaWithJersey)
            ->with('kelas')
            ->orderBy('nama');

        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }

        $siswaWithoutJersey = $siswaQuery->get();

        return view('admin.jersey.report', compact(
            'kelasList', 'kelasId',
            'totalPesanan', 'totalDiterima', 'totalDipesan', 'totalDibatalkan',
            'bySize', 'siswaWithoutJersey'
        ));
    }
}
