<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    /**
     * Tampilkan semua kriteria
     */
    public function index()
    {
        $kriterias = Kriteria::all();
        return response()->json($kriterias);
    }

    /**
     * Simpan kriteria baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $kriteria = Kriteria::create($request->all());

        return response()->json([
            'message' => 'Kriteria berhasil ditambahkan!',
            'data' => $kriteria
        ], 201);
    }

    /**
     * Tampilkan detail kriteria
     */
    public function show($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return response()->json($kriteria);
    }

    /**
     * Update data kriteria
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'sometimes|required|string|max:255'
        ]);

        $kriteria = Kriteria::findOrFail($id);
        $kriteria->update($request->all());

        return response()->json([
            'message' => 'Kriteria berhasil diperbarui!',
            'data' => $kriteria
        ]);
    }

    /**
     * Hapus kriteria
     */
    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();

        return response()->json([
            'message' => 'Kriteria berhasil dihapus!'
        ]);
    }
}
