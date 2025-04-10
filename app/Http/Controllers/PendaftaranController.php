<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::with('user', 'lomba')->get(); // <-- tambahin get()

        if ($pendaftarans->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }


        return response()->json([
            'status' => 'success',
            'data' => $pendaftarans,
        ], 200);
    }

    // API-1: Registrasi-1 (Data Ketua & Lomba)
    public function registrasiKetua(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lomba_id' => 'required|exists:lomba,id',
            'nama_ketua' => 'required|string|max:255',
            'email' => 'required|email|unique:pendaftarans,email',
            'asal_institusi' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'kartu_identitas_ketua' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'nama_team' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('kartu_identitas_ketua')) {
            $imageName = time() . '_ketua_' . $request->file('kartu_identitas_ketua')->getClientOriginalName();
            $request->file('kartu_identitas_ketua')->move(public_path('uploads/pendaftarans'), $imageName);
            $data['kartu_identitas_ketua'] = 'uploads/pendaftarans/' . $imageName;
        }

        $pendaftaran = Pendaftaran::create($data);

        // Update nama dan tahap_user di tabel users
        User::where('id', $data['user_id'])->update([
            'name' => $data['nama_ketua'],
            'tahap_user' => 'Registrasi-1',
        ]);

        return response()->json([
            'message' => 'Pendaftaran Ketua berhasil!',
            'status' => 'success',
            'data' => $pendaftaran,
        ], 200);
    }

    // API-2: Registrasi-2 (Data Anggota Tim)
    public function registrasiAnggota(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:pendaftarans,user_id',
        ]);

        $pendaftaran = Pendaftaran::where('user_id', $data['user_id'])->firstOrFail();

        // Loop untuk menangkap data anggota yang dikirim
        for ($i = 1; $i <= 4; $i++) {
            if ($request->has("nama_anggota_$i")) {
                $pendaftaran["nama_anggota_$i"] = $request->input("nama_anggota_$i");
                $pendaftaran["asal_institusi_anggota_$i"] = $request->input("asal_institusi_anggota_$i");

                if ($request->hasFile("kartu_identitas_anggota_$i")) {
                    $imageName = time() . "_anggota_$i" . $request->file("kartu_identitas_anggota_$i")->getClientOriginalName();
                    $request->file("kartu_identitas_anggota_$i")->move(public_path('uploads/pendaftarans'), $imageName);
                    $pendaftaran["kartu_identitas_anggota_$i"] = 'uploads/pendaftarans/' . $imageName;
                }
            }
        }
        // Update nama dan tahap_user di tabel users
        User::where('id', $data['user_id'])->update([
            'tahap_user' => 'Registrasi-2',
        ]);

        $pendaftaran->save();

        return response()->json([
            'message' => 'Anggota berhasil ditambahkan!',
            'status' => 'success',
            'data' => $pendaftaran,
        ], 200);
    }

    // API-3: Registrasi-3 (Bukti Pembayaran)
    // public function registrasiPembayaran(Request $request)
    // {
    //     $data = $request->validate([
    //         'user_id' => 'required|exists:pendaftarans,user_id',
    //         'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //     ]);

    //     $pendaftaran = Pendaftaran::where('user_id', $data['user_id'])->firstOrFail();

    //     if ($request->hasFile('bukti_pembayaran')) {
    //         $imageName = time() . '_bukti_pembayaran_' . $request->file('bukti_pembayaran')->getClientOriginalName();
    //         $request->file('bukti_pembayaran')->move(public_path('uploads/pendaftarans'), $imageName);
    //         $pendaftaran['bukti_pembayaran'] = 'uploads/pendaftarans/' . $imageName;
    //     }

    //     $pendaftaran->save();

    //     return response()->json([
    //         'message' => 'Bukti pembayaran berhasil diunggah!',
    //         'status' => 'success',
    //         'data' => $pendaftaran,
    //     ], 200);
    // }

    //new API3 Registrasi Bukti Pembayaran
    public function registrasiPembayaran(Request $request)
    {
        // Validasi inputan
        $data = $request->validate([
            'user_id' => 'required|exists:pendaftarans,user_id',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Ambil data pendaftaran
        $pendaftaran = Pendaftaran::with('lomba')->where('user_id', $data['user_id'])->firstOrFail();

        // Handle upload bukti pembayaran
        if ($request->hasFile('bukti_pembayaran')) {
            $imageName = time() . '_bukti_pembayaran_' . $request->file('bukti_pembayaran')->getClientOriginalName();
            $request->file('bukti_pembayaran')->move(public_path('uploads/pendaftarans'), $imageName);
            $filePath = 'uploads/pendaftarans/' . $imageName;

            // Simpan path ke model Pendaftaran
            $pendaftaran->bukti_pembayaran = $filePath;
            $pendaftaran->save();
        }

        // Buat data pembayaran
        $today = now()->format('Ymd');
        $pembayaranId = 'BYR-' . $today . '-' . $pendaftaran->id;

        $pembayaran = Pembayaran::create([
            'id' => $pembayaranId,
            'pendaftaran_id' => $pendaftaran->id,
            'harga' => $pendaftaran->lomba->harga,
            'bukti_pembayaran' => $filePath,
        ]);

        // Update nama dan tahap_user di tabel users
        User::where('id', $data['user_id'])->update([
            'tahap_user' => 'Registrasi-3',
        ]);

        return response()->json([
            'message' => 'Bukti pembayaran berhasil diunggah dan data pembayaran berhasil dibuat!',
            'status' => 'success',
            'pendaftaran' => $pendaftaran,
            'pembayaran' => $pembayaran,
        ], 200);
    }


    // API-4: Registrasi-4 (Bukti Follow Media Sosial)
    public function registrasiBuktiFollow(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:pendaftarans,user_id',
            'bukti_follow_ig_difest' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'bukti_follow_ig_himatikom' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'bukti_follow_tiktok_difest' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'bukti_subscribe_youtube_himatikom' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pendaftaran = Pendaftaran::where('user_id', $data['user_id'])->firstOrFail();

        // Simpan bukti follow
        $buktiFields = [
            'bukti_follow_ig_difest',
            'bukti_follow_ig_himatikom',
            'bukti_follow_tiktok_difest',
            'bukti_subscribe_youtube_himatikom',
        ];

        foreach ($buktiFields as $field) {
            if ($request->hasFile($field)) {
                $imageName = time() . '_' . $field . '_' . $request->file($field)->getClientOriginalName();
                $request->file($field)->move(public_path('uploads/pendaftarans'), $imageName);
                $pendaftaran[$field] = 'uploads/pendaftarans/' . $imageName;
            }
        }

        $pendaftaran->save();

        return response()->json([
            'message' => 'Bukti follow berhasil diunggah!',
            'status' => 'success',
            'data' => $pendaftaran,
        ], 200);
    }

    public function show($id)
    {
        $pendaftaran = Pendaftaran::with('user', 'lomba')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $pendaftaran
        ], 200);
    }
    public function getByUserId($user_id)
    {
        $pendaftaran = Pendaftaran::with('user', 'lomba')->where('user_id', $user_id)->first();

        if (!$pendaftaran) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $pendaftaran,
        ]);
    }


    public function update(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $data = [];

        // Validasi data ketua (Registrasi-1)
        $request->validate([
            'nama_ketua' => 'string|max:255',
            'email' => 'email|unique:pendaftarans,email,' . $pendaftaran->id,
            'asal_institusi' => 'string|max:255',
            'no_hp' => 'string|max:15',
            'nama_team' => 'nullable|string|max:255',
            'lomba_id' => 'exists:lomba,id',
        ]);

        $data += $request->only(['nama_ketua', 'email', 'asal_institusi', 'no_hp', 'nama_team', 'lomba_id']);

        // Ganti file kartu identitas ketua (jika diupload)
        if ($request->hasFile('kartu_identitas_ketua')) {
            if ($pendaftaran->kartu_identitas_ketua && file_exists(public_path($pendaftaran->kartu_identitas_ketua))) {
                unlink(public_path($pendaftaran->kartu_identitas_ketua));
            }
            $imageName = time() . '_ketua_' . $request->file('kartu_identitas_ketua')->getClientOriginalName();
            $request->file('kartu_identitas_ketua')->move(public_path('uploads/pendaftarans'), $imageName);
            $data['kartu_identitas_ketua'] = 'uploads/pendaftarans/' . $imageName;
        }


        // Validasi & update data anggota (Registrasi-2)
        for ($i = 1; $i <= 4; $i++) {
            if ($request->has("nama_anggota_$i")) {
                $data["nama_anggota_$i"] = $request->input("nama_anggota_$i");
                $data["asal_institusi_anggota_$i"] = $request->input("asal_institusi_anggota_$i");

                if ($request->hasFile("kartu_identitas_anggota_$i")) {
                    $old = "kartu_identitas_anggota_$i";
                    if ($pendaftaran->$old && file_exists(public_path($pendaftaran->$old))) {
                        unlink(public_path($pendaftaran->$old));
                    }

                    $imageName = time() . "_anggota_$i" . $request->file("kartu_identitas_anggota_$i")->getClientOriginalName();
                    $request->file("kartu_identitas_anggota_$i")->move(public_path('uploads/pendaftarans'), $imageName);

                    // Perbaikannya disini:
                    $data["kartu_identitas_anggota_$i"] = 'uploads/pendaftarans/' . $imageName;
                }
            }
        }


        // Simpan perubahan
        $pendaftaran->update($data);

        return response()->json([
            'message' => 'Pendaftaran berhasil diperbarui!',
            'status' => 'success',
            'data' => $pendaftaran,
        ], 200);
    }

    public function destroy(Pendaftaran $pendaftaran, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        // Hapus file terkait
        $filesToDelete = [
            'kartu_identitas_ketua',
            'bukti_follow_ig_difest',
            'bukti_follow_ig_himatikom',
            'bukti_follow_tiktok_difest',
            'bukti_subscribe_youtube_himatikom',
            'bukti_pembayaran',
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
            'status' => 'success',
        ], 200);
    }
}
