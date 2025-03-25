<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use App\Http\Requests\InformasiRequest;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index()
    {

        $informasi = informasi::all();

        if ($informasi->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json([
            'status' => 'success',

            'data' => $informasi
        ], 200);
    }


    public function store(InformasiRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $imageName = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('uploads/foto'), $imageName);
            $data['foto'] = 'uploads/foto/' . $imageName;
        }
        // Handle file upload
        if ($request->hasFile('file')) {
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/file'), $fileName);
            $data['file'] = 'uploads/file/' . $fileName;
        }

        $informasi = informasi::create($data);
        return response()->json([
            'message' => 'informasi updated successfully',
            'status' => 'success',
            'data' => $informasi
        ], 200);
    }

    public function show(informasi $informasi)
    {
        return response()->json($informasi, 200);
    }

    public function update(InformasiRequest $request, informasi $informasi)
    {
        $data = $request->validated();

        // Hapus foto lama jika ada foto baru
        if ($request->hasFile('foto')) {
            if ($informasi->foto && file_exists(public_path($informasi->foto))) {
                unlink(public_path($informasi->foto)); // Hapus foto lama
            }
            $imageName = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('uploads/foto'), $imageName);
            $data['foto'] = 'uploads/foto/' . $imageName;
        }

        // Hapus file lama jika ada file baru
        if ($request->hasFile('file')) {
            if ($informasi->file && file_exists(public_path($informasi->file))) {
                unlink(public_path($informasi->file)); // Hapus file lama
            }
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/file'), $fileName);
            $data['file'] = 'uploads/file/' . $fileName;
        }

        $informasi->update($data);

        return response()->json([
            'message' => 'informasi updated successfully',
            'status' => 'success',
            'data' => $informasi
        ], 200);
    }


    public function destroy(informasi $informasi)
    {
        $informasi->delete();
        return response()->json(
            [
                'message' => 'informasi deleted successfully',
                'status' => 'success'
            ],
            200
        );
    }
}
