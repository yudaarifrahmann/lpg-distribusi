<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTruckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('edit master data');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_truk' => 'required|string|max:255',
            'nomor_polisi' => 'required|string|max:50|unique:trucks,nomor_polisi,' . $this->truck->id,
            'kapasitas_tabung' => 'required|integer|min:1',
            'status_kendaraan' => 'required|in:aktif,service,nonaktif',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_truk.required' => 'Nama truk wajib diisi',
            'nomor_polisi.required' => 'Nomor polisi wajib diisi',
            'nomor_polisi.unique' => 'Nomor polisi sudah terdaftar',
            'kapasitas_tabung.required' => 'Kapasitas tabung wajib diisi',
            'kapasitas_tabung.integer' => 'Kapasitas harus berupa angka',
            'status_kendaraan.required' => 'Status kendaraan wajib dipilih',
        ];
    }
}
