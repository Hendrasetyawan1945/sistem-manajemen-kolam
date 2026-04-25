<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ErrorHandlingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (QueryException $e) {
            // Log the detailed error for debugging
            Log::error('Database Error: ' . $e->getMessage(), [
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'user_id' => auth()->id(),
                'url' => $request->url(),
                'method' => $request->method(),
            ]);

            // Return user-friendly error message
            $userMessage = $this->getDatabaseErrorMessage($e);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $userMessage,
                    'errors' => ['database' => [$userMessage]]
                ], 422);
            }

            return back()->withErrors(['database' => $userMessage])->withInput();
            
        } catch (ValidationException $e) {
            // Validation errors are already handled by Laravel
            throw $e;
            
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Unexpected Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'url' => $request->url(),
                'method' => $request->method(),
            ]);

            $userMessage = 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $userMessage,
                    'errors' => ['system' => [$userMessage]]
                ], 500);
            }

            return back()->withErrors(['system' => $userMessage])->withInput();
        }
    }

    /**
     * Get user-friendly database error message
     */
    private function getDatabaseErrorMessage(QueryException $e): string
    {
        $errorCode = $e->errorInfo[1] ?? null;
        $message = $e->getMessage();

        // Handle specific database errors
        if (str_contains($message, 'Duplicate entry')) {
            if (str_contains($message, 'email')) {
                return 'Email sudah digunakan. Silakan gunakan email lain.';
            }
            if (str_contains($message, 'telepon')) {
                return 'Nomor telepon sudah digunakan. Silakan gunakan nomor lain.';
            }
            return 'Data yang dimasukkan sudah ada. Silakan periksa kembali.';
        }

        if (str_contains($message, 'foreign key constraint')) {
            if (str_contains($message, 'kelas_id')) {
                return 'Kelas yang dipilih tidak valid atau tidak ditemukan.';
            }
            if (str_contains($message, 'siswa_id')) {
                return 'Siswa yang dipilih tidak valid atau tidak ditemukan.';
            }
            if (str_contains($message, 'user_id')) {
                return 'Pengguna yang dipilih tidak valid atau tidak ditemukan.';
            }
            return 'Data yang direferensikan tidak ditemukan. Silakan periksa kembali.';
        }

        if (str_contains($message, 'cannot delete or update a parent row')) {
            return 'Data tidak dapat dihapus karena masih digunakan oleh data lain.';
        }

        if (str_contains($message, 'Data too long')) {
            return 'Data yang dimasukkan terlalu panjang. Silakan kurangi jumlah karakter.';
        }

        if (str_contains($message, 'Out of range')) {
            return 'Nilai yang dimasukkan di luar batas yang diizinkan.';
        }

        if (str_contains($message, 'Connection refused') || str_contains($message, 'server has gone away')) {
            return 'Koneksi database bermasalah. Silakan coba lagi dalam beberapa saat.';
        }

        // Default database error message
        return 'Terjadi kesalahan pada database. Silakan periksa data yang dimasukkan.';
    }
}