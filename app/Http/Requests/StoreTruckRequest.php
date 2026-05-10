<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTruckRequest extends FormRequest
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
            'trucks' => 'required|array|min:1',
            'trucks.*.nama_truk' => 'required|string|max:255',
            'trucks.*.nomor_polisi' => 'required|string|max:50|unique:trucks,nomor_polisi',
            'trucks.*.kapasitas_tabung' => 'required|integer|min:1',
            'trucks.*.status_kendaraan' => 'required|in:aktif,service,nonaktif',
        ];
    }

    public function messages(): array
    {
        return [
            'trucks.*.nama_truk.required' => 'Nama truk wajib diisi',
            'trucks.*.nomor_polisi.required' => 'Nomor polisi wajib diisi',
            'trucks.*.nomor_polisi.unique' => 'Nomor polisi sudah terdaftar',
            'trucks.*.kapasitas_tabung.required' => 'Kapasitas tabung wajib diisi',
            'trucks.*.kapasitas_tabung.integer' => 'Kapasitas harus berupa angka',
            'trucks.*.status_kendaraan.required' => 'Status kendaraan wajib dipilih',
        ];
    }
}
