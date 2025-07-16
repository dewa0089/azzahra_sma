<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ActivityHelper;

class UserController extends Controller
{
     public function index()
    {
        $user = User::all();
        return view("user.index")->with("user", $user);
    }

    public function create()
    {
        return view("user.create");
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required'
        ]);

        // Simpan data ke database (hash password)
        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role']
        ]);

        ActivityHelper::log('Tambah Data User', 'Menambahkan user baru dengan nama: ' . $validated['name']);
        return redirect()->route('user.index')->with('success', 'Data User berhasil disimpan');
    }

    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan');
        }
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan');
        }

        // Validasi input
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required'
        ]);

        // Update data ke database
        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        ActivityHelper::log('Mengubah Data User', 'Mengubah data user dengan nama: ' . $user->name);
        return redirect()->route('user.index')->with('success', 'Data User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan');
        }

        ActivityHelper::log('menghapus Data User', 'Menghapus user: ' . $user->name);
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Data User berhasil dihapus');
    }

    public function trash()
{
    $user = User::onlyTrashed()->get();
    return view('user.trash', compact('user'));
}

public function restore($id)
{
    $user = User::withTrashed()->findOrFail($id);
    $user->restore();

    ActivityHelper::log('Restore Data User', 'Merestore user dengan nama: ' . $user->name);
    return redirect()->route('user.index')->with('success', 'Data User berhasil direstore');
}

}
