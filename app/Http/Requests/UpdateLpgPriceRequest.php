<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLpgPriceRequest extends FormRequest
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
            'nama_harga' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_harga.required' => 'Nama harga wajib diisi',
            'harga.required' => 'Harga wajib diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'status.required' => 'Status wajib dipilih',
        ];
    }
}
