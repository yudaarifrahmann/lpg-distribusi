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
        return $this->user()->can('view piutang');
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
            'nominal_pembayaran' => 'required_without:payments|nullable|numeric|min:1',
            'metode_pembayaran' => 'required_without:payments|nullable|in:cash,transfer',
            'bukti_pembayaran' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
            'payments' => 'nullable|array',
            'payments.*.aktif' => 'nullable|boolean',
            'payments.*.nominal_pembayaran' => 'nullable|numeric|min:1',
            'payments.*.metode_pembayaran' => 'nullable|in:cash,transfer',
            'payments.*.bukti_pembayaran' => 'nullable|image|max:2048',
            'payments.*.keterangan' => 'nullable|string',
        ];
    }
}
