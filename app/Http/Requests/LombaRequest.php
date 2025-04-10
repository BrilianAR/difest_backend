<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LombaRequest extends FormRequest
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
            'nama_lomba' => 'required|string',
            'deskripsi' => 'required',
            'harga' => 'required|integer',
            'no_pj' => 'required|string',
            'jenis_pengumpulan' => 'required',
            'jenis_lomba' => 'required',
            'kategori_lomba' => 'required',
            'logo_lomba' => 'nullable|mimes:jpeg,png,jpg',
        ];
    }
}
