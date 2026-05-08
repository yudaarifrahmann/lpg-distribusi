<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create master data');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id|unique:drivers,user_id',
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'role_pekerjaan' => 'required|in:supir,knek',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User wajib dipilih',
            'user_id.exists' => 'User tidak ditemukan',
            'user_id.unique' => 'User sudah terdaftar sebagai driver',
            'nama.required' => 'Nama wajib diisi',
            'nomor_hp.required' => 'Nomor HP wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'role_pekerjaan.required' => 'Role pekerjaan wajib dipilih',
            'status.required' => 'Status wajib dipilih',
        ];
    }
}
