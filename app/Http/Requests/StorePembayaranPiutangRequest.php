<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembayaranPiutangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create piutang'); // or dedicated permission
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'piutang_id' => 'required|exists:piutangs,id',
            'tanggal_pembayaran' => 'required|date',
            'nominal_pembayaran' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer',
            'bukti_pembayaran' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
        ];
    }
}
