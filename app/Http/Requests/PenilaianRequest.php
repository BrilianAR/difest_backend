<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenilaianRequest extends FormRequest
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
            'nilai' => 'required|numeric|min:0|max:100',
            'karya_id' => 'required|exists:hasil_karya,id',
            'daftar_kriteria_id' => 'required|exists:daftar_kriterias,id',
            'juri_id' => 'required|exists:juris,id'
        ];
    }

    /**
     * Pesan error custom
     */
    public function messages(): array
    {
        return [
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.numeric' => 'Nilai harus berupa angka.',
            'nilai.min' => 'Nilai tidak boleh kurang dari 0.',
            'nilai.max' => 'Nilai tidak boleh lebih dari 100.',
            'karya_id.required' => 'Karya wajib dipilih.',
            'karya_id.exists' => 'Karya tidak valid.',
            'daftar_kriteria_id.required' => 'Kriteria wajib dipilih.',
            'daftar_kriteria_id.exists' => 'Kriteria tidak valid.',
            'juri_id.required' => 'Juri wajib dipilih.',
            'juri_id.exists' => 'Juri tidak valid.',
        ];
    }
}
