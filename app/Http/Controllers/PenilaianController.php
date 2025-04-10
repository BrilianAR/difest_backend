<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    /**
     * Tampilkan semua data penilaian
     */
    public function index()
    {
        $penilaians = Penilaian::with([
            'karya.pendaftaran.lomba',  // Relasi karya -> pendaftaran -> lomba
            'daftarKriteria.kriteria',  // Relasi daftarKriteria -> kriteria
            'daftarKriteria.lomba',     // Relasi daftarKriteria -> lomba
            'juri'                      // Relasi user yang menilai (seharusnya juri, bukan users)
        ])->get();


        return response()->json($penilaians);
    }


    /**
     * Simpan penilaian baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'karya_id' => 'required|exists:hasil_karya,id',
            'daftar_kriteria_id' => 'required|exists:daftar_kriterias,id',
            'juri_id' => 'required|exists:users,id'
        ]);

        // Cek apakah sudah ada penilaian dengan kombinasi ini
        $existing = Penilaian::where('karya_id', $request->karya_id)
            ->where('daftar_kriteria_id', $request->daftar_kriteria_id)
            ->where('juri_id', $request->juri_id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Penilaian untuk kriteria ini sudah dimasukkan .',
                'data' => $existing
            ], 409); // 409 Conflict
        }

        $penilaian = Penilaian::create($request->all());

        return response()->json([
            'message' => 'Penilaian berhasil ditambahkan!',
            'data' => $penilaian
        ], 201);
    }

    /**
     * Tampilkan detail penilaian
     */
    public function show($id)
    {
        $penilaian = Penilaian::with(['karya', 'daftarKriteria', 'users'])->findOrFail($id);
        return response()->json($penilaian);
    }

    /**
     * Update data penilaian
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'sometimes|required|numeric|min:0|max:100',
            'karya_id' => 'sometimes|required|exists:hasil_karya,id',
            'daftar_kriteria_id' => 'sometimes|required|exists:daftar_kriterias,id',
            'juri_id' => 'sometimes|required|exists:users,id'
        ]);

        $penilaian = Penilaian::findOrFail($id);
        $penilaian->update($request->all());

        return response()->json([
            'message' => 'Penilaian berhasil diperbarui!',
            'data' => $penilaian
        ]);
    }

    /**
     * Hapus penilaian
     */
    public function destroy($id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $penilaian->delete();

        return response()->json([
            'message' => 'Penilaian berhasil dihapus!'
        ]);
    }
}
