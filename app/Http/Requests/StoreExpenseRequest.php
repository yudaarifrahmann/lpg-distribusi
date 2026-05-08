<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create pengeluaran');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_pengeluaran' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'nama_pengeluaran' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer',
            'keterangan' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB max per image
        ];
    }
}
