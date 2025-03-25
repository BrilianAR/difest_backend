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
            'user_id' => 'required|exists:users,id',
            'lomba_id' => 'required|exists:lomba,id',
            'nama_ketua' => 'required|string|max:255',
            'email' => 'required|email|unique:pendaftarans,email',
            'no_hp' => 'required|string|max:15',
            'asal_institusi' => 'required|string|max:255',
            'kartu_identitas_ketua' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bukti_follow_ig_difest' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'bukti_follow_ig_himatikom' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'bukti_follow_tiktok_difest' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'bukti_subscribe_youtube_himatikom' => 'required|file|mimes:jpg,jpeg,png|max:2048',
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
            'kartu_identitas_ketua.required' => 'Kartu identitas ketua harus diunggah.',
            'bukti_follow_ig_difest.required' => 'Bukti follow IG Difest harus diunggah.',
            'email.unique' => 'Email ini sudah terdaftar.',
        ];
    }
}
