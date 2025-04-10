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
            'pendaftaran_id' => 'nullable|exists:pendaftarans,id',
            'harga' => 'nullable|integer|min:0',
            'bukti_pembayaran' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'status' => 'nullable|in:Tidak Tervalidasi,Tervalidasi'
        ];
    }
}
