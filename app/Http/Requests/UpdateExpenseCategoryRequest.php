<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseCategoryRequest extends FormRequest
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
            'nama_kategori' => 'required|string|max:255|unique:expense_categories,nama_kategori,' . $this->expenseCategory->id,
            'jenis_kategori' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.unique' => 'Nama kategori sudah terdaftar',
            'jenis_kategori.required' => 'Jenis kategori wajib diisi',
        ];
    }
}
