@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10 px-4 sm:px-6 lg:px-8">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
        <div class="flex items-center space-x-5">
            <div class="w-3 h-14 bg-[#1B763B] rounded-full shadow-lg"></div>
            <div>
                <h1 class="font-berkshire text-4xl text-[#473829]">Manajemen Kegiatan</h1>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Atur Jadwal & Waktu Presensi Santriwati</p>
            </div>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('kegiatan.create') }}" class="px-6 py-4 bg-[#473829] text-white rounded-2xl font-black text-xs uppercase shadow-md hover:bg-[#1B763B] transition">
                <i class="ph ph-plus-circle mr-2 text-xl"></i> Tambah Kegiatan
            </a>
        </div>
    </div>

    {{-- SEARCH SECTION --}}
    <div class="mb-8 bg-white p-6 rounded-[30px] shadow-sm border border-gray-100">
        <form action="{{ route('kegiatan.index') }}" method="GET" class="flex flex-wrap items-end gap-6">
            {{-- SEARCH NAMA KEGIATAN --}}
            <div class="flex-1 min-w-[300px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Cari Nama Kegiatan</label>
                <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: Shalat Shubuh, Makan Siang..." 
                           class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-5 py-3.5 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
                </div>
            </div>

            {{-- Tombol Cari --}}
            <button type="submit" class="bg-[#1B763B] text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-[#473829] transition">
                Cari
            </button>
            
            {{-- Reset --}}
            @if(request('search'))
                <a href="{{ route('kegiatan.index') }}" class="text-xs font-bold text-red-400 hover:text-red-600 uppercase underline decoration-2 underline-offset-4 mb-4">Reset</a>
            @endif
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#473829] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/80 border-b">
                        <th class="px-8 py-8 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Kegiatan</th>
                        <th class="px-4 py-8 text-[10px] font-black text-[#1B763B] uppercase text-center tracking-widest">Jam Mulai</th>
                        <th class="px-6 py-8 text-[10px] font-black text-gray-400 uppercase text-center tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kegiatans as $k)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-8 py-6">
                            <p class="font-black text-[#473829] uppercase text-sm leading-tight">{{ $k->nama_kegiatan }}</p>
                            <span class="text-[9px] font-bold text-gray-300 uppercase italic">ID Kegiatan: #{{ $k->id }}</span>
                        </td>
                        
                        <td class="px-4 py-6 text-center">
                            <span class="inline-block px-4 py-2 bg-green-50 text-[#1B763B] rounded-xl font-black text-sm">
                                {{ \Carbon\Carbon::parse($k->jam)->format('H:i') }} WIB
                            </span>
                        </td>

                        <td class="px-6 py-6 text-center">
                            <div class="flex justify-center items-center gap-3">
                                <a href="{{ route('kegiatan.edit', $k->id) }}" class="text-[#473829] hover:text-[#1B763B] transition">
                                    <i class="ph ph-note-pencil text-2xl"></i>
                                </a>
                                <form action="{{ route('kegiatan.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition">
                                        <i class="ph ph-trash text-2xl"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-20 text-center text-gray-400 font-bold italic uppercase text-xs">
                            Kegiatan "{{ request('search') }}" tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection