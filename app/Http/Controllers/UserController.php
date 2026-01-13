<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   public function index(Request $request) { // 2. Tambahkan (Request $request)
    $query = User::query();

    if ($request->filled('search')) {
        $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    $users = $query->latest()->get();

    // 3. PENTING: Gunakan 'user.index' (tanpa S) sesuai folder Anda
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
    public function destroy($id)
{
    // Cegah menghapus diri sendiri yang sedang login
    if (auth()->id() == $id) {
        return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
    }

    $user = User::findOrFail($id);

    // Opsi: Hapus penilaian yang dibuat oleh user ini agar tidak Error Constraint
    // $user->penilaians()->delete(); 

    $user->delete();

    return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus!');
}
}