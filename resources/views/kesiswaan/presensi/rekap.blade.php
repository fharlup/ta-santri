@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10 px-4 sm:px-6 lg:px-8">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
        <div class="flex items-center space-x-5">
            <div class="w-3 h-14 bg-[#1B763B] rounded-full shadow-lg"></div>
            <div>
                <h1 class="font-berkshire text-4xl text-[#473829]">Monitoring Presensi</h1>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Laporan Kehadiran Harian Santriwati</p>
            </div>
        </div>
        
        <a href="{{ route('presensi.export', request()->all()) }}" class="flex items-center px-6 py-4 bg-[#8BC53F] text-white rounded-2xl font-black text-xs uppercase shadow-md hover:bg-[#1B763B] transition">
            <i class="ph ph-file-xls mr-2 text-2xl"></i> Export Excel
        </a>
    </div>

    {{-- FILTER SECTION --}}
    <div class="mb-8 bg-white p-6 rounded-[30px] shadow-sm border border-gray-100">
        <form action="{{ route('presensi.rekap') }}" method="GET" class="flex flex-wrap items-end gap-6">
            
            {{-- INPUT CARI NAMA (BARU) --}}
            <div class="flex-1 min-w-[200px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Cari Nama Santri</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama santri..."
                       class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
            </div>

            {{-- Filter Angkatan --}}
            <div class="flex-1 min-w-[150px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Pilih Angkatan</label>
                <select name="angkatan" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
                    <option value="">Semua Angkatan</option>
                    @foreach($allAngkatan as $a)
                        <option value="{{ $a->nama_angkatan }}" {{ request('angkatan') == $a->nama_angkatan ? 'selected' : '' }}>{{ $a->nama_angkatan }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal --}}
            <div class="flex-1 min-w-[180px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Tanggal Presensi</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') ?? now()->format('Y-m-d') }}" 
                       class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
            </div>

            <button type="submit" class="bg-[#1B763B] text-white px-8 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-[#473829] transition">
                <i class="ph ph-magnifying-glass mr-2"></i> Filter
            </button>
            
            @if(request('angkatan') || request('tanggal') || request('search'))
                <a href="{{ route('presensi.rekap') }}" class="text-xs font-bold text-red-400 hover:text-red-600 uppercase underline decoration-2 underline-offset-4">Reset</a>
            @endif
        </form>
    </div>

    {{-- TABEL MONITORING (KODE TETAP SAMA) --}}
    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#473829] overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-8 py-8 text-[10px] font-black text-gray-400 uppercase sticky left-0 bg-gray-50 z-10">Santriwati</th>
                        @foreach($listKegiatan as $keg)
                            <th class="px-4 py-8 text-[10px] font-black text-[#1B763B] uppercase text-center min-w-[120px]">{{ $keg }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rekapData as $data)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6 sticky left-0 bg-white group-hover:bg-gray-50 z-10 shadow-sm">
                            <p class="font-black text-[#473829] uppercase text-sm leading-tight">{{ $data['nama'] }}</p>
                            <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">{{ $data['angkatan'] }}</span>
                        </td>
                        @foreach($listKegiatan as $keg)
                        <td class="px-4 py-6 text-center">
                            @php $v = $data[$keg]; @endphp
                            <span class="inline-block px-4 py-2 rounded-xl {{ $v == 100 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} font-black text-xs shadow-sm">
                                {{ $v }}%
                            </span>
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($listKegiatan) + 1 }}" class="px-8 py-20 text-center text-gray-400 font-bold italic uppercase tracking-widest text-xs">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection