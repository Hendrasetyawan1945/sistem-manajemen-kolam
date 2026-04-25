<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IuranRutinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $iuranId = $this->route('iuran_rutin') ? $this->route('iuran_rutin')->id : null;
        
        return [
            'siswa_id' => [
                'required',
                'exists:siswa,id'
            ],
            'bulan' => [
                'required',
                'integer',
                'min:1',
                'max:12'
            ],
            'tahun' => [
                'required',
                'integer',
                'min:2020',
                'max:2030'
            ],
            'jumlah' => [
                'required',
                'currency_format',
                'min:1000',
                'max:5000000'
            ],
            'status_bayar' => [
                'required',
                'in:lunas,belum_lunas'
            ],
            'tanggal_bayar' => [
                'required_if:status_bayar,lunas',
                'nullable',
                'date',
                'before_or_equal:today'
            ],
            'metode_pembayaran_id' => [
                'required_if:status_bayar,lunas',
                'nullable',
                'exists:metode_pembayaran,id'
            ]
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'siswa_id.required' => 'Siswa wajib dipilih.',
            'siswa_id.exists' => 'Siswa yang dipilih tidak ditemukan.',
            
            'bulan.required' => 'Bulan wajib dipilih.',
            'bulan.integer' => 'Bulan harus berupa angka.',
            'bulan.min' => 'Bulan minimal 1 (Januari).',
            'bulan.max' => 'Bulan maksimal 12 (Desember).',
            
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2020.',
            'tahun.max' => 'Tahun maksimal 2030.',
            
            'jumlah.required' => 'Jumlah iuran wajib diisi.',
            'jumlah.currency_format' => 'Jumlah iuran harus berupa angka positif.',
            'jumlah.min' => 'Jumlah iuran minimal Rp 1.000.',
            'jumlah.max' => 'Jumlah iuran maksimal Rp 5.000.000.',
            
            'status_bayar.required' => 'Status pembayaran wajib dipilih.',
            'status_bayar.in' => 'Status pembayaran harus Lunas atau Belum Lunas.',
            
            'tanggal_bayar.required_if' => 'Tanggal bayar wajib diisi jika status lunas.',
            'tanggal_bayar.date' => 'Format tanggal bayar tidak valid.',
            'tanggal_bayar.before_or_equal' => 'Tanggal bayar tidak boleh di masa depan.',
            
            'metode_pembayaran_id.required_if' => 'Metode pembayaran wajib dipilih jika status lunas.',
            'metode_pembayaran_id.exists' => 'Metode pembayaran yang dipilih tidak valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'siswa_id' => 'siswa',
            'bulan' => 'bulan',
            'tahun' => 'tahun',
            'jumlah' => 'jumlah iuran',
            'status_bayar' => 'status pembayaran',
            'tanggal_bayar' => 'tanggal bayar',
            'metode_pembayaran_id' => 'metode pembayaran',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for duplicate iuran rutin
            $siswaId = $this->input('siswa_id');
            $bulan = $this->input('bulan');
            $tahun = $this->input('tahun');
            $iuranId = $this->route('iuran_rutin') ? $this->route('iuran_rutin')->id : null;
            
            $exists = \App\Models\IuranRutin::where('siswa_id', $siswaId)
                                         ->where('bulan', $bulan)
                                         ->where('tahun', $tahun)
                                         ->when($iuranId, function($query) use ($iuranId) {
                                             return $query->where('id', '!=', $iuranId);
                                         })
                                         ->exists();
            
            if ($exists) {
                $validator->errors()->add('siswa_id', 'Iuran untuk siswa ini pada bulan dan tahun tersebut sudah ada.');
            }
        });
    }
}