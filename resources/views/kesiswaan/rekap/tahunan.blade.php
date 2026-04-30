@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10">
    {{-- Breadcrumb / Back --}}
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('rekap.index') }}" class="flex items-center text-gray-400 hover:text-[#1B763B] transition font-bold text-xs uppercase tracking-widest">
            <i class="ph ph-arrow-left mr-2 text-xl"></i> Kembali ke Daftar Anak
        </a>
        <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Periode {{ $tahun }}</h2>
    </div>

    {{-- Profil Singkat --}}
    <div class="mb-10 flex items-center space-x-6">
        <div class="w-20 h-20 bg-[#473829] rounded-3xl flex items-center justify-center text-white shadow-xl">
            <i class="ph ph-user-circle text-5xl"></i>
        </div>
        <div>
            <h1 class="font-berkshire text-4xl text-[#473829]">{{ $santri->nama_lengkap }}</h1>
            <p class="text-sm font-bold text-[#1B763B] uppercase tracking-widest">Angkatan {{ $santri->angkatan }} • {{ $santri->kelas }}</p>
        </div>
    </div>

    {{-- Grid 12 Bulan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($rekapTahunan as $r)
        <a href="{{ route('rekap.bulanan', [$santri->id, $r['bulan_ke'], $tahun]) }}" class="group">
            <div class="bg-[#1e1e1e] p-8 rounded-[40px] shadow-2xl border border-white/5 transition-all duration-300 group-hover:scale-[1.03] group-hover:border-[#8BC53F]/50">
                <div class="flex justify-between items-end mb-6">
                    <h3 class="text-2xl font-black text-white uppercase tracking-tighter">{{ $r['nama_bulan'] }}</h3>
                    <span class="text-3xl font-black text-[#8BC53F]">{{ $r['persentase'] }}%</span>
                </div>

                {{-- Progress Bar --}}
                <div class="w-full bg-white/10 h-3 rounded-full overflow-hidden mb-6">
                    <div class="bg-[#8BC53F] h-full rounded-full transition-all duration-1000 shadow-[0_0_15px_rgba(139,197,63,0.5)]" 
                         style="width: {{ $r['persentase'] }}%"></div>
                </div>

                <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-gray-500">
                    <span>{{ $r['total_hadir'] }} Kehadiran</span>
                    <span class="group-hover:text-[#8BC53F] transition-colors">Lihat Detail <i class="ph ph-caret-right ml-1"></i></span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection