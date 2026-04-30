@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10 px-4 sm:px-6 lg:px-8">
    {{-- Breadcrumb / Navigasi Atas --}}
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('rekap.tahunan', [$santri->id, $tahun]) }}" class="flex items-center text-gray-400 hover:text-[#1B763B] transition font-bold text-[10px] uppercase tracking-widest">
            <i class="ph ph-arrow-left mr-2 text-xl"></i> Kembali ke Rekap {{ $tahun }}
        </a>
        
        @php
            $namaBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F');
        @endphp
        
        <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
            <span class="w-2 h-2 bg-[#1B763B] rounded-full animate-pulse"></span>
            <h2 class="text-[10px] font-black text-[#473829] uppercase tracking-[0.2em]">{{ $namaBulan }} {{ $tahun }}</h2>
        </div>
    </div>

    {{-- Header Section dengan Tombol Matriks A-E --}}
    <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h1 class="font-berkshire text-5xl text-[#473829] mb-2">{{ $santri->nama_lengkap }}</h1>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Distribusi Poin Karakter (Target 25% Per Minggu)</p>
        </div>

        {{-- Akses ke Index Penilaian Karakter (Sesuai sketsa image_0e2176.png) --}}
        <a href="{{ route('rekap.penilaian_index', [$santri->id, $tahun, $bulan]) }}" 
           class="flex items-center bg-[#473829] text-white px-8 py-5 rounded-[25px] font-black text-[10px] uppercase tracking-widest shadow-2xl hover:bg-[#1B763B] transition-all transform hover:-translate-y-1 active:scale-95 group">
            <i class="ph-duotone ph-medal mr-3 text-2xl group-hover:animate-bounce"></i>
            Lihat Index Penilaian (A-E)
        </a>
    </div>

    {{-- Grid 4 Minggu --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($rekapMingguan as $m)
        <a href="{{ route('rekap.mingguan', [$santri->id, $bulan, $m['minggu'], $tahun]) }}" class="group">
            <div class="bg-[#1e1e1e] p-10 rounded-[50px] shadow-2xl border border-white/5 transition-all duration-300 group-hover:bg-[#252525] group-hover:border-[#1B763B]/30 relative overflow-hidden">
                
                {{-- Glow Decoration --}}
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#1B763B]/5 rounded-full blur-3xl group-hover:bg-[#1B763B]/10"></div>

                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <h3 class="text-white font-black text-xl uppercase tracking-tighter mb-1">Minggu {{ $m['minggu'] }}</h3>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $m['rentang'] ?? 'Periode Waktu' }}</p>
                    </div>

                    <div class="text-right">
                        <div class="flex items-baseline justify-end space-x-1">
                            {{-- Dinamis: Warna berubah sesuai pencapaian --}}
                            <span class="text-5xl font-black 
                                @if($m['skor'] >= 20) text-[#8BC53F] 
                                @elseif($m['skor'] >= 15) text-amber-400 
                                @else text-red-400 @endif">
                                {{ number_format($m['skor'], 1) }}
                            </span>
                            <span class="text-xl font-bold text-gray-700">/ 25</span>
                        </div>
                        <p class="text-[9px] font-black text-gray-600 uppercase mt-2 tracking-widest">Poin Tercapai</p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mt-8 w-full bg-white/5 h-2 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-1000 
                        @if($m['skor'] >= 20) bg-[#8BC53F] @else bg-amber-500 @endif" 
                        style="width: {{ ($m['skor'] / 25) * 100 }}%"></div>
                </div>
                
                <div class="mt-6 flex justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-[9px] font-black text-[#8BC53F] uppercase tracking-[0.2em]">Klik Untuk Detail Harian <i class="ph ph-arrow-right ml-1"></i></span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Info Footer Card --}}
    <div class="mt-12 bg-white p-10 rounded-[45px] border border-gray-100 flex items-start space-x-8 shadow-sm">
        <div class="w-16 h-16 bg-[#1B763B]/10 rounded-3xl flex items-center justify-center text-[#1B763B] shrink-0">
            <i class="ph-duotone ph-info text-3xl"></i>
        </div>
        <div>
            <h4 class="font-bold text-[#473829] mb-2 text-lg uppercase tracking-tight">Mekanisme Perhitungan Poin</h4>
            <p class="text-[13px] text-gray-400 leading-relaxed max-w-4xl">
                Setiap minggu memiliki bobot maksimal <b>25.0 poin</b> (Total 100% dalam sebulan). Skor dihitung secara otomatis berdasarkan kehadiran santriwati pada 13 kegiatan wajib harian. 
                Ketidakhadiran (Alpha/Tanpa Keterangan) akan memotong poin secara sistematis. Gunakan tombol <b>Index Penilaian</b> di atas untuk meninjau aspek kualitatif seperti Adab dan Kedisiplinan.
            </p>
        </div>
    </div>
</div>
@endsection