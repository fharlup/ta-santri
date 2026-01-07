<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $users = User::latest()->get();
        return view('kesiswaan.user.index', compact('users'));
    }

    public function create() {
        return view('kesiswaan.user.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email'    => $request->username . '@tunasquran.com',
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('kesiswaan.user.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        
        $data = $request->only(['nama_lengkap', 'username', 'role']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('user.index')->with('success', 'Data pengguna diperbarui');
    }
}