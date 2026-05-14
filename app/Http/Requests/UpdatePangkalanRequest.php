<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePangkalanRequest extends FormRequest
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
            'nama_pangkalan' => [
                'required', 
                'string', 
                'max:255', 
                \Illuminate\Validation\Rule::unique('pangkalans', 'nama_pangkalan')
                    ->where('branch_id', auth()->user()->branch_id)
                    ->ignore($this->pangkalan->id)
            ],
            'nama_pemilik' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pangkalan.required' => 'Nama pangkalan wajib diisi',
            'nama_pangkalan.unique' => 'Nama pangkalan sudah terdaftar',
            'nama_pemilik.required' => 'Nama pemilik wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'no_hp.required' => 'Nomor HP wajib diisi',
            'status.required' => 'Status wajib dipilih',
        ];
    }
}
