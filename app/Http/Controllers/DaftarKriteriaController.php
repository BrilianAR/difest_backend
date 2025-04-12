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
            'daftarKriteria.kriteria', // Ambil kriteria terkait
        ])->get();
        return response()->json($daftarKriteria);
    }

    /**
     * Simpan data daftar kriteria baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'lomba_id' => 'required|exists:lomba,id',
            'jenis_kriteria' => 'required'
        ]);

        // Cek apakah data sudah ada di database
        $existingData = DaftarKriteria::where('kriteria_id', $validatedData['kriteria_id'])
            ->where('lomba_id', $validatedData['lomba_id'])
            ->where('jenis_kriteria', $validatedData['jenis_kriteria'])
            ->exists();

        if ($existingData) {
            return response()->json([
                'message' => 'Data sudah ada di database!',
            ], 409); // 409 Conflict
        }

        // Simpan data jika belum ada
        $daftarKriteria = DaftarKriteria::create($validatedData);

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
        $daftarKriteria = DaftarKriteria::with(['kriteria', 'lomba'])->findOrFail($id);
        return response()->json($daftarKriteria);
    }

    /**
     * Update data daftar kriteria
     */
    public function update(Request $request, $id)
    {
        // Cari data berdasarkan ID, jika tidak ditemukan akan otomatis throw 404
        $daftarKriteria = DaftarKriteria::findOrFail($id);

        // Validasi input
        $validatedData = $request->validate([
            'kriteria_id' => 'sometimes|required|exists:kriterias,id',
            'lomba_id' => 'sometimes|required|exists:lomba,id',
            'jenis_kriteria' => 'sometimes|required'
        ]);

        // Cek apakah kombinasi kriteria_id, lomba_id, dan jenis_kriteria sudah ada di database
        $existingData = DaftarKriteria::where('kriteria_id', $validatedData['kriteria_id'] ?? $daftarKriteria->kriteria_id)
            ->where('lomba_id', $validatedData['lomba_id'] ?? $daftarKriteria->lomba_id)
            ->where('jenis_kriteria', $validatedData['jenis_kriteria'] ?? $daftarKriteria->jenis_kriteria)
            ->where('id', '!=', $id) // Pastikan bukan data yang sedang diupdate
            ->exists();

        if ($existingData) {
            return response()->json([
                'message' => 'Data ini sudah ada di database!',
            ], 409); // 409 Conflict
        }

        // Update data
        $daftarKriteria->update($validatedData);

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
        try {
            // Cari daftar kriteria berdasarkan ID, jika tidak ditemukan, lempar exception
            $daftarKriteria = DaftarKriteria::findOrFail($id);

            // Hapus data
            $daftarKriteria->delete();

            // Respons jika penghapusan berhasil
            return response()->json([
                'message' => 'Daftar kriteria berhasil dihapus!'
            ], 200); // Mengembalikan status 200 OK
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika ID tidak ditemukan
            return response()->json([
                'error' => 'Daftar kriteria tidak ditemukan!'
            ], 404); // Mengembalikan status 404 Not Found
        } catch (\Exception $e) {
            // Menangani kesalahan umum lainnya
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data!'
            ], 500); // Mengembalikan status 500 Internal Server Error
        }
    }
}
