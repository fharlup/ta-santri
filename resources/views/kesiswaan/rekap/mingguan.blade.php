@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10">
    {{-- Header & Navigasi Kembali --}}
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('rekap.bulanan', [$santri->id, $bulan]) }}" class="flex items-center text-gray-400 hover:text-[#1B763B] transition font-bold text-xs uppercase tracking-widest">
            <i class="ph ph-arrow-left mr-2 text-xl"></i> Kembali ke Detail Bulanan
        </a>
        <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">
            Minggu {{ $minggu }} - {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ date('Y') }}
        </h2>
    </div>

    {{-- Profil Santriwati --}}
    <div class="mb-10 flex items-end justify-between">
        <div>
            <h1 class="font-berkshire text-4xl text-[#473829] mb-1">{{ $santri->nama_lengkap }}</h1>
            <p class="text-[10px] font-black text-[#1B763B] uppercase tracking-[0.2em]">Laporan Detail Kehadiran & Persentase Harian</p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Data</p>
            <p class="text-xs font-bold text-[#1B763B] uppercase">Real-time Cloud Sync</p>
        </div>
    </div>

    {{-- Iterasi Daftar Hari dalam 1 Minggu --}}
    <div class="space-y-10">
        @foreach($rekapHarian as $hari)
        <div class="bg-white rounded-[40px] p-8 shadow-sm border border-gray-100 overflow-hidden relative">
            
            {{-- Header Kartu Hari --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 border-b border-gray-50 pb-6">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-3">
                        <h3 class="text-2xl font-black text-[#473829] uppercase tracking-tighter">{{ $hari['hari'] }}</h3>
                        @if($hari['persentase'] == 100)
                            <i class="ph-fill ph-seal-check text-[#1B763B] text-2xl"></i>
                        @endif
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">{{ $hari['tanggal'] }}</p>
                </div>
                
                {{-- Statistik Harian (Persen & Jumlah) --}}
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Kehadiran</p>
                        <p class="text-sm font-bold text-[#473829]">{{ $hari['hadir_count'] }} <span class="text-gray-300">/ 13</span></p>
                    </div>

                    {{-- Badge Persentase Harian --}}
                    <div class="flex items-center justify-center min-w-[80px] px-5 py-3 rounded-3xl shadow-lg {{ $hari['persentase'] >= 90 ? 'bg-[#1B763B]' : ($hari['persentase'] >= 70 ? 'bg-amber-500' : 'bg-red-500') }}">
                        <span class="text-lg font-black text-white italic">
                           {{ $hari['persentase'] }}%
                        </span>
                    </div>
                </div>
            </div>

            {{-- Grid 13 Kegiatan Wajib --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-4">
                @foreach($hari['kegiatan'] as $keg)
                @php
                    $isHadir = in_array($keg['status'], ['HADIR', 'TELAT']);
                @endphp
                <div class="group relative flex flex-col items-center p-5 rounded-[30px] border transition-all duration-300 {{ $isHadir ? 'border-green-100 bg-green-50/20 hover:bg-green-50' : 'border-red-100 bg-red-50/20 hover:bg-red-50' }}">
                    
                    {{-- Ikon Status --}}
                    <div class="mb-3">
                        @if($isHadir)
                            <i class="ph-fill ph-check-circle text-green-500 text-3xl"></i>
                        @else
                            <i class="ph-fill ph-x-circle text-red-400 text-3xl"></i>
                        @endif
                    </div>

                    {{-- Nama Kegiatan --}}
                    <span class="text-[9px] font-black uppercase text-center text-gray-600 leading-tight mb-2 tracking-tighter">
                        {{ $keg['nama'] }}
                    </span>

                    {{-- Label Status --}}
                    <span class="text-[8px] font-bold px-3 py-1 rounded-full uppercase tracking-widest {{ $isHadir ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                        {{ $keg['status'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- Footer Info untuk Klien --}}
    <div class="mt-12 bg-[#473829] p-10 rounded-[50px] text-white relative overflow-hidden">
        <div class="relative z-10">
            <h4 class="font-berkshire text-2xl text-[#8BC53F] mb-4">Catatan Sistem</h4>
            <ul class="space-y-3">
                <li class="flex items-start text-xs opacity-80 leading-relaxed">
                    <i class="ph ph-info mr-3 text-lg text-[#8BC53F]"></i>
                    Persentase harian dihitung berdasarkan pembagian total kehadiran terhadap 13 kegiatan wajib harian.
                </li>
                <li class="flex items-start text-xs opacity-80 leading-relaxed">
                    <i class="ph ph-warning-circle mr-3 text-lg text-[#8BC53F]"></i>
                    Status <b>TELAT</b> tetap dihitung sebagai kehadiran (masuk poin persentase) namun terekam dalam database Komdis.
                </li>
            </ul>
        </div>
        {{-- Dekorasi Masjid --}}
        <i class="ph ph-mosque absolute -bottom-10 -right-10 text-[180px] opacity-5"></i>
    </div>
</div>
@endsection