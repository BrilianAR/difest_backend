<?php

namespace App\Http\Controllers;

use App\Http\Requests\PembayaranRequest;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

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

            'data' => $Pembayaran
        ], 200);
    }


    public function store(PembayaranRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('bukti_pembayaran')) {
            $imageName = time() . '_' . $request->file('bukti_pembayaran')->getClientOriginalName();
            $request->file('bukti_pembayaran')->move(public_path('uploads/bukti_pembayaran'), $imageName);
            $data['bukti_pembayaran'] = 'uploads/bukti_pembayaran/' . $imageName;
        }

        $Pembayaran = Pembayaran::create($data);
        return response()->json([
            'message' => 'Pembayaran updated successfully',
            'status' => 'success',
            'data' => $Pembayaran
        ], 200);
    }

    // public function show(Pembayaran $Pembayaran)
    // {
    //     return response()->json($Pembayaran, 200);
    // }

    // public function update(PembayaranRequest $request, Pembayaran $Pembayaran)
    // {
    //     $data = $request->validated();

    //     // Hapus bukti_pembayaran lama jika ada bukti_pembayaran baru
    //     if ($request->hasFile('bukti_pembayaran')) {
    //         if ($Pembayaran->bukti_pembayaran && file_exists(public_path($Pembayaran->bukti_pembayaran))) {
    //             unlink(public_path($Pembayaran->bukti_pembayaran)); // Hapus bukti_pembayaran lama
    //         }
    //         $imageName = time() . '_' . $request->file('bukti_pembayaran')->getClientOriginalName();
    //         $request->file('bukti_pembayaran')->move(public_path('uploads/bukti_pembayaran'), $imageName);
    //         $data['bukti_pembayaran'] = 'uploads/bukti_pembayaran/' . $imageName;
    //     }

    //     $Pembayaran->update($data);

    //     return response()->json([
    //         'message' => 'Pembayaran updated successfully',
    //         'status' => 'success',
    //         'data' => $Pembayaran
    //     ], 200);
    // }


    // public function destroy(Pembayaran $Pembayaran)
    // {
    //     $Pembayaran->delete();
    //     return response()->json(
    //         [
    //             'message' => 'Pembayaran deleted successfully',
    //             'status' => 'success'
    //         ],
    //         200
    //     );
    // }
}
