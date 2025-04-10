<?php

namespace App\Http\Requests;

use App\Rules\IsJuri;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DaftarKriteriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kriteria_id' => 'required|exists:kriterias,id',
            'lomba_id' => 'required|exists:lomba,id',
            'jenis_kriteria' => 'required',
        ];
    }

    /**
     * Pesan error custom
     */
    public function messages(): array
    {
        return [
            'kriteria_id.required' => 'Kriteria wajib dipilih.',
            'kriteria_id.exists' => 'Kriteria tidak valid.',
            'lomba_id.required' => 'Lomba wajib dipilih.',
            'jenis_kriteria.required' => 'Jenis Kriteria wajib dipilih.',
            'lomba_id.exists' => 'Lomba tidak valid.',
        ];
    }
}
