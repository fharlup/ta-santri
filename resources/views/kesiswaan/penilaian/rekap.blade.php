@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10 px-4 sm:px-6 lg:px-8">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
        <div class="flex items-center space-x-5">
            <div class="w-3 h-14 bg-[#1B763B] rounded-full shadow-lg"></div>
            <div>
                <h1 class="font-berkshire text-4xl text-[#473829]">Rekap Penilaian Ahlak</h1>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Monitoring Karakter Santriwati</p>
            </div>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('penilaian.export', request()->all()) }}" class="px-6 py-4 bg-[#8BC53F] text-white rounded-2xl font-black text-xs uppercase shadow-md hover:bg-[#1B763B] transition">
                <i class="ph ph-file-xls mr-2 text-xl"></i> Export Excel
            </a>
            <a href="{{ route('penilaian.create') }}" class="px-6 py-4 bg-[#473829] text-white rounded-2xl font-black text-xs uppercase shadow-md">
                + Input Baru
            </a>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="mb-8 bg-white p-6 rounded-[30px] shadow-sm border border-gray-100">
        <form action="{{ route('penilaian.rekap') }}" method="GET" class="flex flex-wrap items-end gap-6">
            {{-- SEARCH NAMA --}}
            <div class="flex-[2] min-w-[250px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Cari Nama Santriwati</label>
                <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Masukkan nama..." 
                           class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-5 py-3 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
                </div>
            </div>

            {{-- Filter Angkatan --}}
            <div class="flex-1 min-w-[150px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Angkatan</label>
                <select name="angkatan" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
                    <option value="">Semua</option>
                    @foreach($allAngkatan as $a)
                        <option value="{{ $a }}" {{ request('angkatan') == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal --}}
            <div class="flex-1 min-w-[150px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                       class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
            </div>

            {{-- Tombol Cari --}}
            <button type="submit" class="bg-[#1B763B] text-white px-8 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-[#473829] transition">
                Cari Data
            </button>
            
            {{-- Reset --}}
            @if(request('search') || request('angkatan') || request('tanggal'))
                <a href="{{ route('penilaian.rekap') }}" class="text-xs font-bold text-red-400 hover:text-red-600 uppercase underline decoration-2 underline-offset-4 mb-3">Reset</a>
            @endif
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#473829] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/80 border-b">
                        <th class="px-8 py-8 text-[10px] font-black text-gray-400 uppercase sticky left-0 bg-gray-50 z-10">Data Santriwati</th>
                        <th class="px-4 py-8 text-[10px] font-black text-[#1B763B] uppercase text-center">Adab</th>
                        <th class="px-4 py-8 text-[10px] font-black text-[#1B763B] uppercase text-center">Disiplin</th>
                        <th class="px-4 py-8 text-[10px] font-black text-[#1B763B] uppercase text-center">T.Jawab</th>
                        <th class="px-4 py-8 text-[10px] font-black text-blue-500 uppercase text-center">Sabar</th>
                        <th class="px-4 py-8 text-[10px] font-black text-blue-500 uppercase text-center">Jujur</th>
                        <th class="px-6 py-8 text-[10px] font-black text-gray-400 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($penilaians as $p)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-8 py-6 sticky left-0 bg-white group-hover:bg-gray-50 z-10 shadow-sm">
                            <p class="font-black text-[#473829] uppercase text-sm leading-tight">{{ $p->santriwati->nama_lengkap ?? 'Tanpa Nama' }}</p>
                            <span class="text-[9px] font-bold text-gray-300 uppercase italic">
                                Angkatan {{ $p->angkatan }} | {{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}
                            </span>
                        </td>
                        
                        @foreach(['adab', 'disiplin', 'tanggung_jawab', 'integritas_kesabaran', 'integritas_kejujuran'] as $field)
                        <td class="px-4 py-6 text-center">
                            @php
                                $val = $p->$field;
                                $color = $val == 'A' ? 'bg-green-100 text-green-600' : ($val == 'C' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-500');
                            @endphp
                            <span class="inline-block w-8 h-8 leading-8 rounded-lg font-black text-xs {{ $color }}">
                                {{ $val ?? '-' }}
                            </span>
                        </td>
                        @endforeach

                        <td class="px-6 py-6 text-center">
                            <a href="{{ route('penilaian.edit', $p->id) }}" class="text-[#473829] hover:text-[#1B763B] transition">
                                <i class="ph ph-note-pencil text-xl"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-20 text-center text-gray-400 font-bold italic uppercase text-xs">
                            Data tidak ditemukan untuk pencarian "{{ request('search') }}".
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection