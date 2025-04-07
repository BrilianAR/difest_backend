<?php

namespace App\Http\Controllers;

use App\Http\Requests\PembayaranRequest;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;

class PembayaranController extends Controller
{
    public function index()
    {

        $Pembayaran = Pembayaran::all();

        if ($Pembayaran->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json([
            'status' => 'success',

            'data' => $Pembayaran,
        ], 200);
    }

    public function store(PembayaranRequest $request)
    {
        $data = $request->validated();

        // Ambil tanggal & id pendaftaran
        $today = now()->format('Ymd');
        $pendaftaranId = $data['pendaftaran_id'];

        // Buat ID pembayaran
        $data['id'] = 'BYR-' . $today . '-' . $pendaftaranId;

        // Ambil harga dari lomba
        $pendaftaran = Pendaftaran::with('lomba')->findOrFail($pendaftaranId);
        $data['harga'] = $pendaftaran->lomba->harga;

        // Upload file bukti pembayaran
        if ($request->hasFile('bukti_pembayaran')) {
            $imageName = time() . '_' . $request->file('bukti_pembayaran')->getClientOriginalName();
            $request->file('bukti_pembayaran')->move(public_path('uploads/bukti_pembayaran'), $imageName);
            $data['bukti_pembayaran'] = 'uploads/bukti_pembayaran/' . $imageName;
        }

        $pembayaran = Pembayaran::create($data);

        return response()->json([
            'message' => 'Pembayaran berhasil dibuat!',
            'status' => 'success',
            'data' => $pembayaran,
        ], 200);
    }

    public function show(Pembayaran $Pembayaran)
    {
        $pendaftaran = Pembayaran::findOrFail($Pembayaran->id); // Menemukan pembayaran berdasarkan ID
        return response()->json([
            'status' => 'success',
            'data' => $pendaftaran,
        ], 200);
    }

    public function update(PembayaranRequest $request, Pembayaran $Pembayaran)
    {
        $pembayaran = Pembayaran::findOrFail($Pembayaran->id);

        $validated = $request->validate([
            'status' => 'required|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
    
        // Ganti bukti pembayaran kalau ada file baru
        if ($request->hasFile('bukti_pembayaran')) {
            // Hapus file lama
            if ($pembayaran->bukti_pembayaran && file_exists(public_path($pembayaran->bukti_pembayaran))) {
                unlink(public_path($pembayaran->bukti_pembayaran));
            }
    
            $imageName = time() . '_' . $request->file('bukti_pembayaran')->getClientOriginalName();
            $request->file('bukti_pembayaran')->move(public_path('uploads/bukti_pembayaran'), $imageName);
            $validated['bukti_pembayaran'] = 'uploads/bukti_pembayaran/' . $imageName;
        }
    
        // Update status & bukti (kalau ada)
        $pembayaran->update($validated);
    
        return response()->json([
            'message' => 'Pembayaran berhasil diperbarui!',
            'status' => 'success',
            'data' => $pembayaran,
        ], 200);
    }

    public function destroy(Pembayaran $Pembayaran)
    {
        $pembayaran = Pembayaran::findOrFail($Pembayaran->id);

        // Hapus file jika ada
        if ($pembayaran->bukti_pembayaran && file_exists(public_path($pembayaran->bukti_pembayaran))) {
            unlink(public_path($pembayaran->bukti_pembayaran));
        }

        // Hapus data pembayaran
        $pembayaran->delete();

        return response()->json([
            'message' => 'Pembayaran berhasil dihapus!',
            'status' => 'success',
        ], 200);
    }

}
