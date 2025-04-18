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
            'juri_id' => 'required|exists:users,id'
        ];
    }
}