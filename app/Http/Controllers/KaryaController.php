<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Http\Requests\KaryaRequest;
use App\Models\Lomba;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class KaryaController extends Controller
{
    public function index(Request $request)
    {
        // Cek apakah request punya parameter pendaftaran_id
        if ($request->has('pendaftaran_id')) {
            $pendaftaranId = $request->query('pendaftaran_id');

            // Cari karya berdasarkan pendaftaran_id
            $karya = Karya::with('pendaftaran', 'pendaftaran.lomba', 'pendaftaran.user')
                ->where('pendaftaran_id', $pendaftaranId)
                ->first(); // asumsi 1 karya per pendaftaran, kalau bisa banyak, ubah ke ->get()

            if (!$karya) {
                return response()->json([
                    'status' => 'not found',
                    'message' => 'Karya dengan pendaftaran_id tersebut tidak ditemukan!',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data karya berhasil diambil!',
                'data' => $karya
            ], 200);
        }

        // Kalau tidak ada parameter pendaftaran_id, return semua karya (opsional)
        $allKarya = Karya::with('pendaftaran', 'pendaftaran.lomba', 'pendaftaran.user')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Semua data karya berhasil diambil!',
            'data' => $allKarya
        ], 200);
    }


    public function store(KaryaRequest $request)
    {
        $lomba = Lomba::findOrFail($request->lomba_id);
        $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);

        User::where('id', $pendaftaran->user_id)->update([
            'tahap_user' => 'Pengumpulan Karya',
        ]);


        $data = [
            'pendaftaran_id' => $request->pendaftaran_id,
            'judul_karya' => $request->judul_karya,
            'deskripsi' => $request->deskripsi,
            'status_karya' => 'Belum Diverifikasi',
            'karya' => null,
            'link_karya' => null
        ];

        if ($request->hasFile('karya')) {
            $data['karya'] = $this->uploadFile($request->file('karya'), $lomba->jenis_pengumpulan);
        } elseif ($request->filled('link_karya')) {
            $data['link_karya'] = $request->link_karya;
        }

        if ($request->hasFile('keaslian_karya')) {
            $data['keaslian_karya'] = $this->uploadFile($request->file('keaslian_karya'), 'keaslian_karya');
        }

        $karya = Karya::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Karya berhasil disimpan!',
            'data' => $karya
        ], 201);
    }

    public function show($id)
    {
        $karya = Karya::with('pendaftaran', 'pendaftaran.lomba', 'pendaftaran.user')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Detail karya berhasil diambil!',
            'data' => $karya
        ], 200);
    }


    public function update(KaryaRequest $request, $id)
    {
        // Cari data karya berdasarkan ID, kalau nggak ketemu balikin 404
        $karya = Karya::find($id);

        if (!$karya) {
            return response()->json(['message' => 'Karya tidak ditemukan'], 404);
        }

        // Cari data lomba berdasarkan request->lomba_id, digunakan untuk menentukan jenis pengumpulan
        $lomba = Lomba::findOrFail($request->lomba_id);

        // Data awal yang akan di-update, jika tidak ada input baru maka gunakan data lama
        $data = [
            'pendaftaran_id' => $request->pendaftaran_id,
            'judul_karya'    => $request->judul_karya ?? $karya->judul_karya,
            'deskripsi'      => $request->deskripsi ?? $karya->deskripsi,
            'status_karya'   => $request->status_karya ?? $karya->status_karya,
        ];

        $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);

        User::where('id', $pendaftaran->user_id)->update([
            'tahap_user' => 'Tuntas',
        ]);


        // Jika upload file karya baru (file fisik)
        if ($request->hasFile('karya')) {
            // Hapus file lama jika ada
            if ($karya->karya && File::exists(public_path($karya->karya))) {
                File::delete(public_path($karya->karya));
            }

            // Upload file baru ke folder berdasarkan jenis pengumpulan (e.g., "link", "file")
            $data['karya'] = $this->uploadFile($request->file('karya'), $lomba->jenis_pengumpulan);
            $data['link_karya'] = null; // Kosongkan link karena pakai file
        }

        // Jika user isi link_karya
        elseif ($request->filled('link_karya')) {
            // Hapus file fisik jika sebelumnya upload via file
            if ($karya->karya && File::exists(public_path($karya->karya))) {
                File::delete(public_path($karya->karya));
            }

            // Simpan link dan kosongkan path file
            $data['link_karya'] = $request->link_karya;
            $data['karya'] = null;
        }

        // Jika user upload file keaslian karya
        if ($request->hasFile('keaslian_karya')) {
            // Hapus file lama jika ada
            if ($karya->keaslian_karya && File::exists(public_path($karya->keaslian_karya))) {
                File::delete(public_path($karya->keaslian_karya));
            }

            // Upload file baru ke folder 'keaslian_karya'
            $data['keaslian_karya'] = $this->uploadFile($request->file('keaslian_karya'), 'keaslian_karya');
        }

        // Lakukan update data karya dengan data yang sudah disiapkan
        $karya->update($data);

        // Response sukses
        return response()->json([
            'status'  => 'success',
            'message' => 'Karya berhasil diperbarui!',
            'data'    => $karya
        ], 200);
    }


    private function uploadFile($file, $folder)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads/' . $folder);

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true, true);
        }

        $file->move($destinationPath, $fileName);

        return 'uploads/' . $folder . '/' . $fileName;
    }
}
