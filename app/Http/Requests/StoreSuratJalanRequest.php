<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratJalanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create surat jalan');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nomor_surat_jalan' => 'required|string|unique:surat_jalans,nomor_surat_jalan',
            'tanggal_berangkat' => 'required|date',
            'penebusan_id' => 'nullable|exists:penebusans,id',
            'truck_id' => 'nullable|exists:trucks,id',
            'driver_id' => 'required', // Can be ID or 'tembak'
            'knek_id' => 'nullable', // Can be ID or 'tembak'
            'jumlah_tabung' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
            'foto_surat_jalan' => 'nullable|image|max:2048',
            'muat_dari_gudang' => 'nullable|boolean',
            'is_supir_tembak' => 'nullable|boolean',
            'nama_supir_tembak' => 'required_if:is_supir_tembak,1|nullable|string',
            'no_hp_supir_tembak' => 'required_if:is_supir_tembak,1|nullable|string',
            'alamat_supir_tembak' => 'nullable|string',
            'is_knek_tembak' => 'nullable|boolean',
            'nama_knek_tembak' => 'required_if:is_knek_tembak,1|nullable|string',
            'no_hp_knek_tembak' => 'required_if:is_knek_tembak,1|nullable|string',
            'alamat_knek_tembak' => 'nullable|string',
        ];
    }
}
