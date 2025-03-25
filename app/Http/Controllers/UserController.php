<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan semua user
    public function index()
    {
        $user = User::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data users berhasil diambil',
            'data' => $user
        ], 200);
    }

    // Menyimpan user baru
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']); // Hash password sebelum disimpan

        $user = User::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil ditambahkan',
            'data' => $user
        ], 201);
    }

    // Menampilkan user berdasarkan ID
    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    // Mengupdate user
    public function update(UserRequest $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user['id'],
            'password' => 'nullable|min:6', // Supaya password bisa kosong saat edit
            'role' => 'required|in:user,admin,juri',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Jangan update password kalau kosong
        }

        $user->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil diperbarui',
            'data' => $user
        ], 200);
    }

    // Menghapus user
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil dihapus'
        ], 200);
    }
}
