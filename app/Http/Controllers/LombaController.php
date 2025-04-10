<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Http\Requests\LombaRequest;
use Illuminate\Http\Request;

class LombaController extends Controller
{
    public function index()
    {
        $Lomba = Lomba::all();

        if ($Lomba->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json([
            'data' => $Lomba
        ], 200);
    }


    public function store(LombaRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo_lomba')) {
            $imageName = time() . '_' . $request->file('logo_lomba')->getClientOriginalName();
            $request->file('logo_lomba')->move(public_path('uploads/logo_lomba'), $imageName);
            $data['logo_lomba'] = 'uploads/logo_lomba/' . $imageName;
        }

        $Lomba = Lomba::create($data);
        return response()->json([
            'message' => 'Lomba updated successfully',
            'status' => 'success',
            'data' => $Lomba
        ], 200);
    }

    public function show(Lomba $Lomba)
    {
        return response()->json($Lomba, 200);
    }

    public function update(LombaRequest $request, Lomba $Lomba)
    {
        $data = $request->validated();

        // Hapus logo_lomba lama jika ada logo_lomba baru
        if ($request->hasFile('logo_lomba')) {
            if ($Lomba->logo_lomba && file_exists(public_path($Lomba->logo_lomba))) {
                unlink(public_path($Lomba->logo_lomba)); // Hapus logo_lomba lama
            }
            $imageName = time() . '_' . $request->file('logo_lomba')->getClientOriginalName();
            $request->file('logo_lomba')->move(public_path('uploads/logo_lomba'), $imageName);
            $data['logo_lomba'] = 'uploads/logo_lomba/' . $imageName;
        }


        $Lomba->update($data);

        return response()->json([
            'message' => 'Lomba updated successfully',
            'status' => 'success',
            'data' => $Lomba
        ], 200);
    }


    public function destroy(Lomba $Lomba)
    {
        $Lomba->delete();
        return response()->json(
            [
                'message' => 'Lomba deleted successfully',
                'status' => 'success'
            ],
            200
        );
    }
}
