<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Enhanced logging for different exception types
            $context = [
                'user_id' => auth()->id(),
                'url' => request()->url(),
                'method' => request()->method(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ];

            if ($e instanceof QueryException) {
                Log::error('Database Error', array_merge($context, [
                    'sql' => $e->getSql(),
                    'bindings' => $e->getBindings(),
                    'error_code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));
            } elseif ($e instanceof ValidationException) {
                Log::info('Validation Error', array_merge($context, [
                    'errors' => $e->errors(),
                    'input' => request()->except($this->dontFlash),
                ]));
            } elseif ($e instanceof AuthenticationException) {
                Log::warning('Authentication Error', array_merge($context, [
                    'message' => $e->getMessage(),
                ]));
            } elseif ($e instanceof AuthorizationException) {
                Log::warning('Authorization Error', array_merge($context, [
                    'message' => $e->getMessage(),
                ]));
            } else {
                Log::error('System Error', array_merge($context, [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]));
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle specific exceptions with user-friendly messages
        if ($e instanceof QueryException) {
            $userMessage = $this->getDatabaseErrorMessage($e);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $userMessage,
                    'errors' => ['database' => [$userMessage]]
                ], 422);
            }

            return back()->withErrors(['database' => $userMessage])->withInput();
        }

        return parent::render($request, $e);
    }

    /**
     * Get user-friendly database error message
     */
    private function getDatabaseErrorMessage(QueryException $e): string
    {
        $message = $e->getMessage();

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
            return 'Data yang direferensikan tidak ditemukan atau tidak valid.';
        }

        if (str_contains($message, 'cannot delete or update a parent row')) {
            return 'Data tidak dapat dihapus karena masih digunakan oleh data lain.';
        }

        return 'Terjadi kesalahan pada database. Silakan periksa data yang dimasukkan.';
    }
}
