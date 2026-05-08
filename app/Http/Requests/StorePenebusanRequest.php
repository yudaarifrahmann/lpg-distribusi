<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenebusanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create penebusan');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nomor_do' => 'required|string|unique:penebusans,nomor_do',
            'tanggal_penebusan' => 'required|date',
            'schedule_agreement_id' => 'required|exists:schedule_agreements,id',
            'truck_id' => 'required|exists:trucks,id',
            'driver_id' => 'required|exists:drivers,id',
            'foto_nota' => 'nullable|image|max:2048', // max 2MB
        ];
    }
}
