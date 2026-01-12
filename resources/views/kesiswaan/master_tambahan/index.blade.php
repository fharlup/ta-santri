@extends('layouts.app')

@section('content')
<div class="space-y-8">
    {{-- 1. Header Halaman --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-[#473829]">Master Angkatan & Kelas</h1>
            <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Manajemen Data Dasar Santriwati</p>
        </div>
        <div class="w-14 h-14 bg-[#8BC53F]/10 rounded-2xl flex items-center justify-center text-[#8BC53F]">
            <i class="ph-fill ph-database text-3xl"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- SEKSI ANGKATAN --}}
        <div class="space-y-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h2 class="font-bold text-[#1B763B] mb-6 flex items-center text-lg">
                    <i class="ph ph-plus-circle mr-3 text-2xl"></i> Tambah Angkatan
                </h2>
                <form action="{{ route('master_tambahan.angkatan.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-[0.2em]">Nama Angkatan</label>
                        <input type="text" name="nama_angkatan" required 
                            class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-gray-50 focus:bg-white focus:border-[#1B763B] outline-none transition-all" 
                            placeholder="Contoh: Angkatan 10">
                    </div>
                    <button type="submit" class="w-full bg-[#473829] text-white p-4 rounded-2xl font-bold hover:bg-[#1B763B] transition-all shadow-md uppercase text-xs tracking-widest">
                        Simpan Angkatan
                    </button>
                </form>
            </div>

            {{-- Tabel Angkatan --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Nama Angkatan</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black uppercase text-gray-400 tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($angkatans as $a)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-bold text-[#473829]">{{ $a->nama_angkatan }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('master_tambahan.angkatan.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600 transition">
                                        <i class="ph ph-trash text-xl"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="px-6 py-10 text-center text-gray-400 italic text-xs font-medium uppercase tracking-tighter">Belum ada data angkatan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SEKSI KELAS --}}
        <div class="space-y-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h2 class="font-bold text-[#1B763B] mb-6 flex items-center text-lg">
                    <i class="ph ph-plus-circle mr-3 text-2xl"></i> Tambah Kelas
                </h2>
                <form action="{{ route('master_tambahan.kelas.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-[0.2em]">Nama Kelas</label>
                        <input type="text" name="nama_kelas" required 
                            class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-gray-50 focus:bg-white focus:border-[#1B763B] outline-none transition-all" 
                            placeholder="Contoh: Kelas 7A">
                    </div>
                    <button type="submit" class="w-full bg-[#473829] text-white p-4 rounded-2xl font-bold hover:bg-[#1B763B] transition-all shadow-md uppercase text-xs tracking-widest">
                        Simpan Kelas
                    </button>
                </form>
            </div>

            {{-- Tabel Kelas --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Nama Kelas</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black uppercase text-gray-400 tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($kelass as $k)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-bold text-[#473829]">{{ $k->nama_kelas }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('master_tambahan.kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600 transition">
                                        <i class="ph ph-trash text-xl"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="px-6 py-10 text-center text-gray-400 italic text-xs font-medium uppercase tracking-tighter">Belum ada data kelas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection