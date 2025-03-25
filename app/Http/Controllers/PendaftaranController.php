<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Http\Requests\PendaftaranRequest;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::all();

        if ($pendaftarans->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $pendaftarans
        ], 200);
    }

    public function store(PendaftaranRequest $request)
    {
        $data = $request->validated();

        // Simpan kartu identitas ketua
        if ($request->hasFile('kartu_identitas_ketua')) {
            $imageName = time() . '_ketua_' . $request->file('kartu_identitas_ketua')->getClientOriginalName();
            $request->file('kartu_identitas_ketua')->move(public_path('uploads/pendaftarans'), $imageName);
            $data['kartu_identitas_ketua'] = 'uploads/pendaftarans/' . $imageName;
        }

        // Simpan kartu identitas anggota
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("kartu_identitas_anggota_$i")) {
                $imageName = time() . "_anggota_$i_" . $request->file("kartu_identitas_anggota_$i")->getClientOriginalName();
                $request->file("kartu_identitas_anggota_$i")->move(public_path('uploads/pendaftarans'), $imageName);
                $data["kartu_identitas_anggota_$i"] = 'uploads/pendaftarans/' . $imageName;
            }
        }

        // Simpan bukti follow
        $buktiFields = [
            'bukti_follow_ig_difest',
            'bukti_follow_ig_himatikom',
            'bukti_follow_tiktok_difest',
            'bukti_subscribe_youtube_himatikom'
        ];

        foreach ($buktiFields as $field) {
            if ($request->hasFile($field)) {
                $imageName = time() . '_' . $field . '_' . $request->file($field)->getClientOriginalName();
                $request->file($field)->move(public_path('uploads/pendaftarans'), $imageName);
                $data[$field] = 'uploads/pendaftarans/' . $imageName;
            }
        }

        $pendaftaran = Pendaftaran::create($data);

        return response()->json([
            'message' => 'Pendaftaran berhasil!',
            'status' => 'success',
            'data' => $pendaftaran
        ], 201);
    }

    public function show(Pendaftaran $pendaftaran)
    {
        return response()->json([
            'status' => 'success',
            'data' => $pendaftaran
        ], 200);
    }

    public function update(PendaftaranRequest $request, Pendaftaran $pendaftaran)
    {
        $data = $request->validated();

        // Update kartu identitas ketua
        if ($request->hasFile('kartu_identitas_ketua')) {
            if ($pendaftaran->kartu_identitas_ketua && file_exists(public_path($pendaftaran->kartu_identitas_ketua))) {
                unlink(public_path($pendaftaran->kartu_identitas_ketua));
            }
            $imageName = time() . '_ketua_' . $request->file('kartu_identitas_ketua')->getClientOriginalName();
            $request->file('kartu_identitas_ketua')->move(public_path('uploads/pendaftarans'), $imageName);
            $data['kartu_identitas_ketua'] = 'uploads/pendaftarans/' . $imageName;
        }

        // Update kartu identitas anggota
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("kartu_identitas_anggota_$i")) {
                if ($pendaftaran["kartu_identitas_anggota_$i"] && file_exists(public_path($pendaftaran["kartu_identitas_anggota_$i"]))) {
                    unlink(public_path($pendaftaran["kartu_identitas_anggota_$i"]));
                }
                $imageName = time() . "_anggota_$i_" . $request->file("kartu_identitas_anggota_$i")->getClientOriginalName();
                $request->file("kartu_identitas_anggota_$i")->move(public_path('uploads/pendaftarans'), $imageName);
                $data["kartu_identitas_anggota_$i"] = 'uploads/pendaftarans/' . $imageName;
            }
        }

        // Update bukti follow
        foreach ($buktiFields as $field) {
            if ($request->hasFile($field)) {
                if ($pendaftaran->$field && file_exists(public_path($pendaftaran->$field))) {
                    unlink(public_path($pendaftaran->$field));
                }
                $imageName = time() . '_' . $field . '_' . $request->file($field)->getClientOriginalName();
                $request->file($field)->move(public_path('uploads/pendaftarans'), $imageName);
                $data[$field] = 'uploads/pendaftarans/' . $imageName;
            }
        }

        $pendaftaran->update($data);

        return response()->json([
            'message' => 'Pendaftaran updated successfully',
            'status' => 'success',
            'data' => $pendaftaran
        ], 200);
    }

    public function destroy(Pendaftaran $pendaftaran)
    {
        // Hapus file terkait
        $filesToDelete = [
            'kartu_identitas_ketua',
            'bukti_follow_ig_difest',
            'bukti_follow_ig_himatikom',
            'bukti_follow_tiktok_difest',
            'bukti_subscribe_youtube_himatikom'
        ];

        for ($i = 1; $i <= 4; $i++) {
            $filesToDelete[] = "kartu_identitas_anggota_$i";
        }

        foreach ($filesToDelete as $file) {
            if ($pendaftaran->$file && file_exists(public_path($pendaftaran->$file))) {
                unlink(public_path($pendaftaran->$file));
            }
        }

        $pendaftaran->delete();

        return response()->json([
            'message' => 'Pendaftaran deleted successfully',
            'status' => 'success'
        ], 200);
    }
}
