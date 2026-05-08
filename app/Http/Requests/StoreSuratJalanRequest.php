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
            'penebusan_id' => 'required|exists:penebusans,id',
            'truck_id' => 'required|exists:trucks,id',
            'driver_id' => 'required|exists:drivers,id',
            'knek_id' => 'nullable|exists:drivers,id',
            'jumlah_tabung' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
            'foto_surat_jalan' => 'nullable|image|max:2048',
        ];
    }
}
