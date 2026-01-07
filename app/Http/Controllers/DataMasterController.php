<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santriwati;
use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Kegiatan;

class DataMasterController extends Controller
{
    /**
     * DASHBOARD: Menampilkan statistik utama
     */
    public function dashboard()
    {
        $data = [
            'total_santri'   => Santriwati::count(),
            'total_pengguna' => User::count(),
            'total_kegiatan' => Kegiatan::count(),
            // Mengambil 5 santri yang baru didaftarkan
            'santri_terbaru' => Santriwati::latest()->take(5)->get(),
        ];

        return view('kesiswaan.dashboard', $data);
    }

    /**
     * INDEX: Daftar semua santriwati
     */
    public function index()
    {
        $santris = Santriwati::latest()->get();
        return view('kesiswaan.santri.index', compact('santris'));
    }

    /**
     * CREATE: Form tambah santri (Sinkron dengan Master Angkatan & Kelas)
     */
    public function create()
    {
        $angkatans = Angkatan::all();
        $kelas = Kelas::all();
        
        return view('kesiswaan.santri.create', compact('angkatans', 'kelas'));
    }

    /**
     * STORE: Simpan data santri baru
     */
   public function store(Request $request)
{
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'username'     => 'required|string|unique:santriwatis,username',
        'password'     => 'required|min:6',
        'rfid_id'      => 'required|string|unique:santriwatis,rfid_id',
        'angkatan'     => 'required',
        'kelas'        => 'required',
    ]);

    Santriwati::create([
        'nama_lengkap' => strtoupper($request->nama_lengkap),
        'nim'          => $request->nim,
        'username'     => $request->username,
        'password'     => $request->password,
        'rfid_id'      => $request->rfid_id,
        'angkatan'     => $request->angkatan,
        'kelas'        => $request->kelas,
    ]);

    return redirect()->route('santri.index')->with('success', 'Santriwati berhasil ditambahkan!');
}
    /**
     * EDIT: Form edit santri (Sinkron dengan Master Angkatan & Kelas)
     */
    public function edit($id)
    {
        $santri = Santriwati::findOrFail($id);
        $angkatans = Angkatan::all();
        $kelas = Kelas::all();

        return view('kesiswaan.santri.edit', compact('santri', 'angkatans', 'kelas'));
    }

    /**
     * UPDATE: Perbarui data santri
     */
   public function update(Request $request, $id)
{
    $santri = Santriwati::findOrFail($id);

    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        
        'username'     => 'required|string|unique:santriwatis,username,' . $id,
        'rfid_id'      => 'required|string|unique:santriwatis,rfid_id,' . $id,
        'angkatan'     => 'required',
        'kelas'        => 'required',
    ]);

    $data = [
        'nama_lengkap' => strtoupper($request->nama_lengkap),
        'username'     => $request->username,
        'rfid_id'      => $request->rfid_id,
        'angkatan'     => $request->angkatan,
        'kelas'        => $request->kelas,
    ];

    // Update password hanya jika diisi
    if ($request->filled('password')) {
        $data['password'] = $request->password;
    }

    $santri->update($data);

    return redirect()->route('santri.index')->with('success', 'Data santriwati diperbarui!');
}

    /**
     * DESTROY: Hapus data santri
     */
    public function destroy($id)
    {
        $santri = Santriwati::findOrFail($id);
        $santri->delete();

        return redirect()->route('santri.index')->with('success', 'Data santriwati telah dihapus!');
    }
}