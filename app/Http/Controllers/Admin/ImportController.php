<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentCsvExport;

class ImportController extends Controller
{
    /**
     * Show the import page
     */
    public function index()
    {
        $kelasList = Kelas::pluck('nama', 'id')->prepend('-- Pilih Kelas --', '');
        
        return view('admin.import.index', compact('kelasList'));
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-siswa.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // CSV Headers
            fputcsv($file, [
                'nama',
                'tanggal_lahir',
                'jenis_kelamin',
                'alamat',
                'nama_orang_tua',
                'telepon_orang_tua',
                'kelas_id',
                'status'
            ]);
            
            // Sample data
            fputcsv($file, [
                'John Doe',
                '2010-05-15',
                'L',
                'Jl. Contoh No. 123',
                'Jane Doe',
                '081234567890',
                '1',
                'aktif'
            ]);
            
            fputcsv($file, [
                'Jane Smith',
                '2011-03-20',
                'P',
                'Jl. Sample No. 456',
                'John Smith',
                '081987654321',
                '2',
                'aktif'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Preview CSV data before import
     */
    public function preview(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'csv_file.required' => 'File CSV harus dipilih.',
            'csv_file.file' => 'File yang dipilih tidak valid.',
            'csv_file.mimes' => 'File harus berformat CSV.',
            'csv_file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        // Read CSV file
        $csvData = [];
        $errors = [];
        $validRows = [];
        $invalidRows = [];
        
        if (($handle = fopen($path, 'r')) !== FALSE) {
            // Skip BOM if present
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }
            
            // Read header
            $header = fgetcsv($handle);
            
            // Expected headers
            $expectedHeaders = [
                'nama', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 
                'nama_orang_tua', 'telepon_orang_tua', 'kelas_id', 'status'
            ];
            
            // Validate headers
            if ($header !== $expectedHeaders) {
                return back()->withErrors([
                    'csv_file' => 'Format header CSV tidak sesuai. Gunakan template yang disediakan.'
                ]);
            }
            
            $rowNumber = 2; // Start from row 2 (after header)
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                $rowData = array_combine($header, $data);
                
                // Validate row data
                $validator = $this->validateRowData($rowData, $rowNumber);
                
                if ($validator->fails()) {
                    $invalidRows[] = [
                        'row' => $rowNumber,
                        'data' => $rowData,
                        'errors' => $validator->errors()->all()
                    ];
                } else {
                    $validRows[] = [
                        'row' => $rowNumber,
                        'data' => $rowData
                    ];
                }
                
                $rowNumber++;
            }
            
            fclose($handle);
        }
        
        // Store valid rows in session for import
        session(['import_valid_rows' => $validRows]);
        
        // Get class names for display
        $kelasNames = Kelas::pluck('nama', 'id');
        
        return view('admin.import.preview', compact(
            'validRows', 'invalidRows', 'kelasNames'
        ));
    }

    /**
     * Import CSV data from session
     */
    public function import(Request $request)
    {
        $validRows = session('import_valid_rows', []);
        
        if (empty($validRows)) {
            return redirect()->route('admin.import.index')
                           ->withErrors(['csv_file' => 'Tidak ada data untuk diimport. Silakan upload file CSV terlebih dahulu.']);
        }
        
        $imported = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($validRows as $rowData) {
                $data = $rowData['data'];
                
                // Create user account
                $user = User::create([
                    'name' => $data['nama'],
                    'email' => $this->generateEmail($data['nama']),
                    'password' => bcrypt('password123'),
                    'role' => 'siswa',
                    'nama' => $data['nama'],
                    'telepon' => $data['telepon_orang_tua'],
                ]);
                
                // Create siswa record
                Siswa::create([
                    'user_id' => $user->id,
                    'nama' => $data['nama'],
                    'tanggal_lahir' => Carbon::parse($data['tanggal_lahir']),
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'alamat' => $data['alamat'],
                    'nama_orang_tua' => $data['nama_orang_tua'],
                    'telepon_orang_tua' => $data['telepon_orang_tua'],
                    'kelas_id' => $data['kelas_id'],
                    'status' => $data['status'],
                ]);
                
                $imported++;
            }
            
            DB::commit();
            
            // Clear session data
            session()->forget('import_valid_rows');
            
            $message = "Berhasil mengimpor {$imported} siswa.";
            
            return redirect()->route('admin.import.index')
                           ->with('success', $message);
                           
        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->withErrors([
                'import' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Validate individual row data
     */
    private function validateRowData($data, $rowNumber)
    {
        $rules = [
            'nama' => 'required|string|min:3|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string|max:500',
            'nama_orang_tua' => 'required|string|min:3|max:255',
            'telepon_orang_tua' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:aktif,cuti,nonaktif',
        ];

        $messages = [
            'nama.required' => 'Nama siswa harus diisi.',
            'nama.min' => 'Nama siswa minimal 3 karakter.',
            'nama.max' => 'Nama siswa maksimal 255 karakter.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            'nama_orang_tua.required' => 'Nama orang tua harus diisi.',
            'nama_orang_tua.min' => 'Nama orang tua minimal 3 karakter.',
            'nama_orang_tua.max' => 'Nama orang tua maksimal 255 karakter.',
            'telepon_orang_tua.required' => 'Telepon orang tua harus diisi.',
            'telepon_orang_tua.min' => 'Telepon orang tua minimal 10 digit.',
            'telepon_orang_tua.max' => 'Telepon orang tua maksimal 15 digit.',
            'telepon_orang_tua.regex' => 'Telepon orang tua hanya boleh berisi angka.',
            'kelas_id.required' => 'Kelas harus dipilih.',
            'kelas_id.exists' => 'Kelas tidak ditemukan.',
            'status.required' => 'Status harus diisi.',
            'status.in' => 'Status harus aktif, cuti, atau nonaktif.',
        ];

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Generate unique email for student
     */
    private function generateEmail($nama)
    {
        $baseEmail = strtolower(str_replace(' ', '.', $nama)) . '@siswa.klubrenang.com';
        $email = $baseEmail;
        $counter = 1;
        
        while (User::where('email', $email)->exists()) {
            $email = strtolower(str_replace(' ', '.', $nama)) . $counter . '@siswa.klubrenang.com';
            $counter++;
        }
        
        return $email;
    }

    /**
     * Export students to CSV for round-trip functionality
     */
    public function exportCsv(Request $request)
    {
        $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'status' => 'nullable|in:aktif,cuti,nonaktif',
        ]);

        $kelasId = $request->kelas_id;
        $status = $request->status;
        
        $filename = 'siswa-export-' . date('Y-m-d') . '.csv';
        
        return Excel::download(new StudentCsvExport($kelasId, $status), $filename, \Maatwebsite\Excel\Excel::CSV);
    }
}