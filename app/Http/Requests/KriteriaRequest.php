<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KriteriaRequest extends FormRequest
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
            'nama' => 'required|string|max:255'
        ];
    }

    /**
     * Pesan error custom
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kriteria wajib diisi.',
            'nama.string' => 'Nama kriteria harus berupa teks.',
            'nama.max' => 'Nama kriteria tidak boleh lebih dari 255 karakter.'
        ];
    }
}
