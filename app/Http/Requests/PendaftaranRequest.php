<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranRequest extends FormRequest
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
        $rules = [
            'user_id' => 'nullable|exists:users,id',
            'lomba_id' => 'nullable|exists:lomba,id',
            'nama_ketua' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:pendaftarans,email',
            'no_hp' => 'nullable|string|max:15',
            'asal_institusi' => 'nullable|string|max:255',
            'kartu_identitas_ketua' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'bukti_follow_ig_difest' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'bukti_follow_ig_himatikom' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'bukti_follow_tiktok_difest' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'bukti_subscribe_youtube_himatikom' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ];

        for ($i = 1; $i <= 4; $i++) {
            $rules["nama_anggota_$i"] = 'nullable|string|max:255';
            $rules["asal_institusi_anggota_$i"] = 'nullable|string|max:255';
            $rules["kartu_identitas_anggota_$i"] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        return $rules;
    }
    /**
     * Custom error messages.
     */
    public function messages()
    {
        return [
            'kartu_identitas_ketua.nullable' => 'Kartu identitas ketua harus diunggah.',
            'bukti_pembayaran.nullable' => 'Bukti Pembayaran harus diunggah.',
            'bukti_follow_ig_difest.nullable' => 'Bukti follow IG Difest harus diunggah.',
            'email.unique' => 'Email ini sudah terdaftar.',
        ];
    }
}
