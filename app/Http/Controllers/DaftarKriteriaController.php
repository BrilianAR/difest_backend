<?php

namespace App\Http\Controllers;

use App\Models\DaftarKriteria;
use App\Models\Lomba;
use Illuminate\Http\Request;

class DaftarKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $daftarKriteria = Lomba::with([
            'daftarKriteria.kriteria', // Ambil kriteria
            'daftarKriteria.user' // Ambil juri
        ])->get();
        return response()->json($daftarKriteria);
    }

    /**
     * Simpan data daftar kriteria baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'lomba_id' => 'required|exists:lomba,id'
        ]);

        $daftarKriteria = DaftarKriteria::create($request->all());

        return response()->json([
            'message' => 'Daftar kriteria berhasil ditambahkan!',
            'data' => $daftarKriteria
        ], 201);
    }

    /**
     * Tampilkan detail daftar kriteria
     */
    public function show($id)
    {
        $daftarKriteria = DaftarKriteria::with(['kriteria', 'lomba', 'users'])->findOrFail($id);
        return response()->json($daftarKriteria);
    }

    /**
     * Update data daftar kriteria
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kriteria_id' => 'sometimes|required|exists:kriterias,id',
            'lomba_id' => 'sometimes|required|exists:lomba,id',
            'user_id' => 'sometimes|required|exists:users,id'
        ]);

        $daftarKriteria = DaftarKriteria::findOrFail($id);
        $daftarKriteria->update($request->all());

        return response()->json([
            'message' => 'Daftar kriteria berhasil diperbarui!',
            'data' => $daftarKriteria
        ]);
    }

    /**
     * Hapus daftar kriteria
     */
    public function destroy($id)
    {
        $daftarKriteria = DaftarKriteria::findOrFail($id);
        $daftarKriteria->delete();

        return response()->json([
            'message' => 'Daftar kriteria berhasil dihapus!'
        ]);
    }
}
