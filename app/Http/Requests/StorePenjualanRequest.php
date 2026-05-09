<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenjualanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create penjualan');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'pangkalan_id' => $this->pangkalan_id ?: null,
            'pangkalan_nama' => $this->pangkalan_nama ? trim($this->pangkalan_nama) : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_penjualan' => 'required|date',
            'surat_jalan_id' => 'required|exists:surat_jalans,id',
            'pangkalan_id' => 'nullable|required_without:pangkalan_nama|exists:pangkalans,id',
            'pangkalan_nama' => 'nullable|required_without:pangkalan_id|string|max:255',
            'lpg_price_id' => 'required|exists:lpg_prices,id',
            'jumlah_tabung' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,utang',
            'catatan' => 'nullable|string',
            'tanggal_jatuh_tempo' => 'required_if:metode_pembayaran,utang|nullable|date|after_or_equal:tanggal_penjualan',
        ];
    }
}
