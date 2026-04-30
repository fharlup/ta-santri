@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10">
    <div class="mb-12">
        <a href="{{ route('rekap.index') }}" class="flex items-center text-gray-400 hover:text-[#1B763B] transition font-bold text-[10px] uppercase tracking-widest">
            <i class="ph ph-arrow-left mr-2 text-xl"></i> Kembali ke Daftar Santriwati
        </a>
    </div>

    <div class="text-center mb-16">
        <h1 class="font-berkshire text-5xl text-[#473829] mb-3">{{ $santri->nama_lengkap }}</h1>
        <p class="text-xs font-black text-[#1B763B] uppercase tracking-[0.4em]">Pilih Tahun Akademik Laporan</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
        @foreach($daftarTahun as $thn)
        <a href="{{ route('rekap.tahunan', [$santri->id, $thn['value']]) }}" class="group">
            <div class="bg-white p-10 rounded-[50px] shadow-sm border border-gray-100 text-center transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 hover:border-[#1B763B]">
                <div class="w-20 h-20 bg-gray-50 rounded-3xl mx-auto mb-6 flex items-center justify-center text-gray-300 group-hover:bg-[#1B763B]/10 group-hover:text-[#1B763B] transition-colors">
                    <i class="ph-duotone ph-calendar-blank text-5xl"></i>
                </div>
                
                <h3 class="text-3xl font-black text-[#473829] mb-1 italic">{{ $thn['value'] }}</h3>
                <p class="text-[10px] font-black text-[#1B763B] uppercase tracking-widest">{{ $thn['label'] }}</p>
                
                <div class="mt-8 py-3 px-6 bg-gray-50 rounded-2xl group-hover:bg-[#1B763B] group-hover:text-white transition-all">
                    <span class="text-[10px] font-black uppercase tracking-widest">Buka Rekap</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection