<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'coach']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $siswaId = $this->route('siswa') ? $this->route('siswa')->id : null;
        
        return [
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
                'after:1990-01-01'
            ],
            'jenis_kelamin' => [
                'required',
                'in:L,P'
            ],
            'alamat' => [
                'nullable',
                'string',
                'max:500'
            ],
            'nama_orang_tua' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'telepon_orang_tua' => [
                'required',
                'phone_number',
                Rule::unique('siswa', 'telepon_orang_tua')->ignore($siswaId)
            ],
            'kelas_id' => [
                'required',
                'exists:kelas,id'
            ],
            'status' => [
                'required',
                'in:aktif,cuti,nonaktif'
            ],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png',
                'max:2048'
            ]
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama siswa wajib diisi.',
            'nama.min' => 'Nama siswa minimal 3 karakter.',
            'nama.max' => 'Nama siswa maksimal 255 karakter.',
            'nama.regex' => 'Nama siswa hanya boleh berisi huruf dan spasi.',
            
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tanggal_lahir.after' => 'Tanggal lahir tidak boleh sebelum tahun 1990.',
            
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
            
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            
            'nama_orang_tua.required' => 'Nama orang tua wajib diisi.',
            'nama_orang_tua.min' => 'Nama orang tua minimal 3 karakter.',
            'nama_orang_tua.max' => 'Nama orang tua maksimal 255 karakter.',
            'nama_orang_tua.regex' => 'Nama orang tua hanya boleh berisi huruf dan spasi.',
            
            'telepon_orang_tua.required' => 'Telepon orang tua wajib diisi.',
            'telepon_orang_tua.phone_number' => 'Format telepon orang tua tidak valid. Gunakan 10-15 digit angka.',
            'telepon_orang_tua.unique' => 'Nomor telepon sudah digunakan oleh siswa lain.',
            
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
            
            'status.required' => 'Status siswa wajib dipilih.',
            'status.in' => 'Status harus Aktif, Cuti, atau Non-aktif.',
            
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPEG, JPG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama' => 'nama siswa',
            'tanggal_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'alamat' => 'alamat',
            'nama_orang_tua' => 'nama orang tua',
            'telepon_orang_tua' => 'telepon orang tua',
            'kelas_id' => 'kelas',
            'status' => 'status',
            'foto' => 'foto',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json([
                    'message' => 'Data yang dimasukkan tidak valid.',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}