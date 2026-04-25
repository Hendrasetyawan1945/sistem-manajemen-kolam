<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KelasRequest extends FormRequest
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
        $kelasId = $this->route('kelas') ? $this->route('kelas')->id : null;
        
        return [
            'nama_kelas' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('kelas', 'nama')->ignore($kelasId)
            ],
            'jadwal' => [
                'required',
                'string',
                'max:255'
            ],
            'kapasitas' => [
                'required',
                'integer',
                'min:1',
                'max:50'
            ],
            'biaya_bulanan' => [
                'required',
                'currency_format',
                'min:0',
                'max:10000000'
            ],
            'coach_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = \App\Models\User::find($value);
                    if (!$user || $user->role !== 'coach') {
                        $fail('Pelatih yang dipilih tidak valid.');
                    }
                }
            ]
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.min' => 'Nama kelas minimal 3 karakter.',
            'nama_kelas.max' => 'Nama kelas maksimal 100 karakter.',
            'nama_kelas.unique' => 'Nama kelas sudah digunakan.',
            
            'jadwal.required' => 'Jadwal kelas wajib diisi.',
            'jadwal.max' => 'Jadwal kelas maksimal 255 karakter.',
            
            'kapasitas.required' => 'Kapasitas kelas wajib diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas minimal 1 siswa.',
            'kapasitas.max' => 'Kapasitas maksimal 50 siswa.',
            
            'biaya_bulanan.required' => 'Biaya bulanan wajib diisi.',
            'biaya_bulanan.currency_format' => 'Biaya bulanan harus berupa angka positif.',
            'biaya_bulanan.min' => 'Biaya bulanan tidak boleh negatif.',
            'biaya_bulanan.max' => 'Biaya bulanan maksimal Rp 10.000.000.',
            
            'coach_id.required' => 'Pelatih wajib dipilih.',
            'coach_id.exists' => 'Pelatih yang dipilih tidak ditemukan.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama_kelas' => 'nama kelas',
            'jadwal' => 'jadwal',
            'kapasitas' => 'kapasitas',
            'biaya_bulanan' => 'biaya bulanan',
            'coach_id' => 'pelatih',
        ];
    }
}