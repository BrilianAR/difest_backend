<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
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
            'id' => 'sometimes|string|max:20|unique:pembayarans,id',
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'harga' => 'sometimes|integer|min:0',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png|max:2048'
        ];
    }
}
