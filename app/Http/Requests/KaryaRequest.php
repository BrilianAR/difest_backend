<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KaryaRequest extends FormRequest
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
            //
            'pendaftaran_id' => 'nullable', //required
            'lomba_id' => 'nullable', //required
            'judul_karya' => 'nullable|string', //required
            'deskripsi' => 'nullable|string', //required
            'karya' => 'nullable',
            'link_karya' => 'nullable',
            'keaslian_karya' => 'nullable', //required
        ];
    }
}
