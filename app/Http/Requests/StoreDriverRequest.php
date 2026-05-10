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
            'drivers' => 'required|array|min:1',
            'drivers.*.user_id' => 'required|exists:users,id|unique:drivers,user_id',
            'drivers.*.nama' => 'required|string|max:255',
            'drivers.*.nomor_hp' => 'required|string|max:20',
            'drivers.*.alamat' => 'required|string',
            'drivers.*.role_pekerjaan' => 'required|in:supir,knek',
            'drivers.*.truck_id' => 'nullable|exists:trucks,id',
            'drivers.*.status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function messages(): array
    {
        return [
            'drivers.*.user_id.required' => 'User wajib dipilih',
            'drivers.*.user_id.exists' => 'User tidak ditemukan',
            'drivers.*.user_id.unique' => 'User sudah terdaftar sebagai driver',
            'drivers.*.nama.required' => 'Nama wajib diisi',
            'drivers.*.nomor_hp.required' => 'Nomor HP wajib diisi',
            'drivers.*.alamat.required' => 'Alamat wajib diisi',
            'drivers.*.role_pekerjaan.required' => 'Role pekerjaan wajib dipilih',
            'drivers.*.status.required' => 'Status wajib dipilih',
        ];
    }

}
