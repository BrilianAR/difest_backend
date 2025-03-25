<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JuriRequest extends FormRequest
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
            'nama' => 'required|string|max:255',
            'lomba_id' => 'required|exists:lomba,id'
        ];
    }

    /**
     * Pesan error custom
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama juri wajib diisi.',
            'nama.string' => 'Nama juri harus berupa teks.',
            'nama.max' => 'Nama juri tidak boleh lebih dari 255 karakter.',
            'lomba_id.required' => 'Lomba wajib dipilih.',
            'lomba_id.exists' => 'Lomba tidak valid.',
        ];
    }
}
