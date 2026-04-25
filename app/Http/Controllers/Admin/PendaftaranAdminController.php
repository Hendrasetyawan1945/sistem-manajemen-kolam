<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PendaftaranAdminController extends Controller
{
    /** Daftar semua pendaftaran */
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['kelas', 'diprosesOleh']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pendaftaran = $query
            ->orderByRaw("CASE status WHEN 'menunggu' THEN 0 WHEN 'disetujui' THEN 1 ELSE 2 END")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalMenunggu  = Pendaftaran::where('status', 'menunggu')->count();
        $totalDisetujui = Pendaftaran::where('status', 'disetujui')->count();
        $totalDitolak   = Pendaftaran::where('status', 'ditolak')->count();

        return view('admin.pendaftaran.index', compact(
            'pendaftaran', 'totalMenunggu', 'totalDisetujui', 'totalDitolak'
        ));
    }

    /** Detail pendaftaran */
    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load(['kelas', 'siswa', 'diprosesOleh']);
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();

        return view('admin.pendaftaran.show', compact('pendaftaran', 'kelasList'));
    }

    /** Setujui pendaftaran → buat User + Siswa */
    public function approve(Request $request, Pendaftaran $pendaftaran)
    {
        if ($pendaftaran->status !== 'menunggu') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya');
        }

        $request->validate([
            'kelas_id'      => 'required|exists:kelas,id',
            'catatan_admin' => 'nullable|string|max:500',
        ], [
            'kelas_id.required' => 'Kelas wajib dipilih sebelum menyetujui',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat akun User
            $user = User::create([
                'name'      => $pendaftaran->nama,
                'email'     => $pendaftaran->email,
                'password'  => $pendaftaran->password, // sudah di-hash saat daftar
                'role'      => 'siswa',
                'is_active' => true,
            ]);

            // 2. Buat data Siswa
            $siswa = Siswa::create([
                'user_id'        => $user->id,
                'kelas_id'       => $request->kelas_id,
                'nama'           => $pendaftaran->nama,
                'tanggal_lahir'  => $pendaftaran->tanggal_lahir,
                'jenis_kelamin'  => $pendaftaran->jenis_kelamin,
                'alamat'         => $pendaftaran->alamat,
                'nama_ortu'      => $pendaftaran->nama_ortu,
                'telepon_ortu'   => $pendaftaran->telepon_ortu,
                'status'         => 'aktif',
                'tanggal_daftar' => now()->toDateString(),
            ]);

            // 3. Update status pendaftaran
            $pendaftaran->update([
                'status'        => 'disetujui',
                'siswa_id'      => $siswa->id,
                'catatan_admin' => $request->catatan_admin,
                'diproses_oleh' => Auth::id(),
                'diproses_pada' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.pendaftaran.show', $pendaftaran->id)
                ->with('success', "Pendaftaran {$pendaftaran->nama} berhasil disetujui. Akun siswa telah dibuat.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    /** Tolak pendaftaran */
    public function reject(Request $request, Pendaftaran $pendaftaran)
    {
        if ($pendaftaran->status !== 'menunggu') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya');
        }

        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi',
        ]);

        $pendaftaran->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
            'diproses_oleh' => Auth::id(),
            'diproses_pada' => now(),
        ]);

        return redirect()->route('admin.pendaftaran.show', $pendaftaran->id)
            ->with('success', "Pendaftaran {$pendaftaran->nama} telah ditolak.");
    }

    /** Hapus pendaftaran yang ditolak */
    public function destroy(Pendaftaran $pendaftaran)
    {
        if ($pendaftaran->status === 'disetujui') {
            return back()->with('error', 'Pendaftaran yang sudah disetujui tidak dapat dihapus');
        }

        $pendaftaran->delete();

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Data pendaftaran berhasil dihapus');
    }
}
