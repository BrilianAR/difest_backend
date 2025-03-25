<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Http\Requests\KaryaRequest;
use App\Models\Lomba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class KaryaController extends Controller
{
    public function store(KaryaRequest $request)
    {
        $lomba = Lomba::findOrFail($request->lomba_id);

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

    public function update(KaryaRequest $request, $id)
    {
        $karya = Karya::findOrFail($id);
        $lomba = Lomba::findOrFail($request->lomba_id);

        $data = [
            'pendaftaran_id' => $request->pendaftaran_id,
            'judul_karya' => $request->judul_karya ?? $karya->judul_karya,
            'deskripsi' => $request->deskripsi ?? $karya->deskripsi,
            'status_karya' => $request->status_karya ?? $karya->status_karya,
        ];

        if ($request->hasFile('karya')) {
            if ($karya->karya && File::exists(public_path($karya->karya))) {
                File::delete(public_path($karya->karya));
            }
            $data['karya'] = $this->uploadFile($request->file('karya'), $lomba->jenis_pengumpulan);
            $data['link_karya'] = null;
        } elseif ($request->filled('link_karya')) {
            if ($karya->karya && File::exists(public_path($karya->karya))) {
                File::delete(public_path($karya->karya));
            }
            $data['link_karya'] = $request->link_karya;
            $data['karya'] = null;
        }

        if ($request->hasFile('keaslian_karya')) {
            if ($karya->keaslian_karya && File::exists(public_path($karya->keaslian_karya))) {
                File::delete(public_path($karya->keaslian_karya));
            }
            $data['keaslian_karya'] = $this->uploadFile($request->file('keaslian_karya'), 'keaslian_karya');
        }

        $karya->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Karya berhasil diperbarui!',
            'data' => $karya
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
