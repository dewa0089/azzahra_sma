<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'role' => 'required'
        ]);

        // Simpan data ke database
        User::create($validated);

        return redirect()->route('user.index')->with('success', 'Data User berhasil disimpan');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'role' => 'required'
        ]);

        // Update data ke database
        User::find($id)->update($validated);

        return redirect()->route('user.index')->with('success', 'Data Barang berhasil diupdate');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('user.index')->with('success', 'Data Barang berhasil dihapus');
    }
}
