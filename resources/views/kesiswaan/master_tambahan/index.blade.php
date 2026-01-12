@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10 px-4 sm:px-6 lg:px-8">
    {{-- HEADER HALAMAN --}}
    <div class="flex items-center space-x-5 mb-10">
        <div class="w-3 h-14 bg-[#1B763B] rounded-full shadow-lg"></div>
        <div>
            <h1 class="font-berkshire text-4xl text-[#473829]">Master Angkatan & Kelas</h1>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pengaturan Data Dasar Sistem</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        
        {{-- SEKSI 1: MANAJEMEN ANGKATAN --}}
        <div>
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-black text-[#473829] uppercase tracking-tighter text-xl">Daftar Angkatan</h2>
            </div>

            {{-- FORM SEARCH ANGKATAN --}}
            <div class="mb-5 bg-white p-4 rounded-3xl shadow-sm border border-gray-100">
                <form action="{{ route('master.index') }}" method="GET" class="flex gap-3">
                    <div class="flex-1 relative">
                        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search_angkatan" value="{{ request('search_angkatan') }}" 
                               placeholder="Cari tahun angkatan..." 
                               class="w-full bg-gray-50 border-none rounded-xl pl-11 pr-4 py-2 text-sm font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B]">
                    </div>
                    <button type="submit" class="bg-[#1B763B] text-white px-5 py-2 rounded-xl font-black text-[10px] uppercase shadow-md">Cari</button>
                    @if(request('search_angkatan'))
                        <a href="{{ route('master.index') }}" class="text-[10px] font-bold text-red-400 uppercase flex items-center underline">Reset</a>
                    @endif
                </form>
            </div>

            {{-- FORM TAMBAH ANGKATAN --}}
            <form action="{{ route('master.angkatan.store') }}" method="POST" class="mb-5 flex gap-3">
                @csrf
                <input type="text" name="nama_angkatan" placeholder="Input angkatan baru (contoh: 2024)" required
                       class="flex-1 bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold outline-none focus:ring-2 focus:ring-[#1B763B]">
                <button type="submit" class="bg-[#473829] text-white px-5 py-2 rounded-xl font-black text-[10px] uppercase shadow-md">+ Simpan</button>
            </form>

            {{-- TABEL ANGKATAN --}}
            <div class="bg-white rounded-[40px] shadow-xl border-t-[10px] border-[#473829] overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase">Tahun Angkatan</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($angkatans as $a)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-black text-[#473829] text-sm uppercase">{{ $a->nama_angkatan }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('master.angkatan.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus angkatan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600"><i class="ph ph-trash text-xl"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="py-10 text-center text-gray-300 font-bold uppercase text-[10px]">Data tidak ditemukan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SEKSI 2: MANAJEMEN KELAS --}}
        <div>
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-black text-[#473829] uppercase tracking-tighter text-xl">Daftar Kelas</h2>
            </div>

            {{-- FORM SEARCH KELAS --}}
            <div class="mb-5 bg-white p-4 rounded-3xl shadow-sm border border-gray-100">
                <form action="{{ route('master.index') }}" method="GET" class="flex gap-3">
                    <div class="flex-1 relative">
                        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search_kelas" value="{{ request('search_kelas') }}" 
                               placeholder="Cari nama kelas..." 
                               class="w-full bg-gray-50 border-none rounded-xl pl-11 pr-4 py-2 text-sm font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B]">
                    </div>
                    <button type="submit" class="bg-[#1B763B] text-white px-5 py-2 rounded-xl font-black text-[10px] uppercase shadow-md">Cari</button>
                    @if(request('search_kelas'))
                        <a href="{{ route('master.index') }}" class="text-[10px] font-bold text-red-400 uppercase flex items-center underline">Reset</a>
                    @endif
                </form>
            </div>

            {{-- FORM TAMBAH KELAS --}}
            <form action="{{ route('master.kelas.store') }}" method="POST" class="mb-5 flex gap-3">
                @csrf
                <input type="text" name="nama_kelas" placeholder="Input kelas baru (contoh: 7A)" required
                       class="flex-1 bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold outline-none focus:ring-2 focus:ring-[#1B763B]">
                <button type="submit" class="bg-[#473829] text-white px-5 py-2 rounded-xl font-black text-[10px] uppercase shadow-md">+ Simpan</button>
            </form>

            {{-- TABEL KELAS --}}
            <div class="bg-white rounded-[40px] shadow-xl border-t-[10px] border-[#1B763B] overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase">Nama Kelas</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($kelass as $k)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-black text-[#473829] text-sm uppercase">{{ $k->nama_kelas }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('master.kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600"><i class="ph ph-trash text-xl"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="py-10 text-center text-gray-300 font-bold uppercase text-[10px]">Data tidak ditemukan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection