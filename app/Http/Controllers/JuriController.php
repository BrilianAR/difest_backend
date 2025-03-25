<?php

namespace App\Http\Controllers;

use App\Models\Juri;
use Illuminate\Http\Request;

class JuriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $juris = Juri::with('lomba')->get();
        return response()->json($juris);
    }

    /**
     * Simpan data juri baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lomba_id' => 'required|exists:lomba,id'
        ]);

        $juri = Juri::create($request->all());

        return response()->json([
            'message' => 'Juri berhasil ditambahkan!',
            'data' => $juri
        ], 201);
    }

    /**
     * Tampilkan detail juri
     */
    public function show($id)
    {
        $juri = Juri::with('lomba')->findOrFail($id);
        return response()->json($juri);
    }

    /**
     * Update data juri
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'lomba_id' => 'sometimes|required|exists:lomba,id'
        ]);

        $juri = Juri::findOrFail($id);
        $juri->update($request->all());

        return response()->json([
            'message' => 'Juri berhasil diperbarui!',
            'data' => $juri
        ]);
    }

    /**
     * Hapus juri
     */
    public function destroy($id)
    {
        $juri = Juri::findOrFail($id);
        $juri->delete();

        return response()->json([
            'message' => 'Juri berhasil dihapus!'
        ]);
    }
}
