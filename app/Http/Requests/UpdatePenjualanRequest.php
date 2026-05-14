<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenjualanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->can('edit penjualan')) {
            return true;
        }

        $penjualan = $this->route('penjualan');
        if ($this->user()->hasRole('supir_knek') && $penjualan) {
            return $penjualan->driver_id === optional($this->user()->driver)->id;
        }

        return false;
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
            'pangkalan_id' => 'required|exists:pangkalans,id',
            'jumlah_tabung' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:lunas,belum_lunas,cicilan',
            'catatan' => 'nullable|string',
        ];
    }
}
