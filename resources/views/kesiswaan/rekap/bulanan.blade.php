@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10">
    {{-- Breadcrumb / Back --}}
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('rekap.tahunan', $santri->id) }}" class="flex items-center text-gray-400 hover:text-[#1B763B] transition font-bold text-xs uppercase tracking-widest">
            <i class="ph ph-arrow-left mr-2 text-xl"></i> Kembali ke Rekap Tahunan
        </a>
        @php
            $namaBulan = \Carbon\Carbon::create(null, $bulan)->translatedFormat('F');
        @endphp
        <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">{{ $namaBulan }} {{ date('Y') }}</h2>
    </div>

    {{-- Header Page --}}
    <div class="mb-12">
        <h1 class="font-berkshire text-4xl text-[#473829] mb-2">{{ $santri->nama_lengkap }}</h1>
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Distribusi Poin Karakter (Target 25% Per Minggu)</p>
    </div>

    {{-- Grid 4 Minggu (Sesuai Desain Ustadz) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($rekapMingguan as $m)
        <a href="{{ route('rekap.mingguan', [$santri->id, $bulan, $m['minggu']]) }}" class="group">
            <div class="bg-[#1e1e1e] p-10 rounded-[50px] shadow-2xl border border-white/5 transition-all duration-300 group-hover:bg-[#252525] group-hover:border-[#8BC53F]/30 relative overflow-hidden">
                
                {{-- Glow Effect di Pojok --}}
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#8BC53F]/5 rounded-full blur-3xl group-hover:bg-[#8BC53F]/10"></div>

                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <h3 class="text-white font-black text-xl uppercase tracking-tighter mb-1">Minggu {{ $m['minggu'] }}</h3>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $m['rentang'] }}</p>
                    </div>

                    <div class="text-right">
                        <div class="flex items-baseline justify-end space-x-1">
                            {{-- Angka Skor (Misal 20) --}}
                            <span class="text-5xl font-black {{ $m['skor'] >= 20 ? 'text-[#8BC53F]' : ($m['skor'] >= 15 ? 'text-amber-400' : 'text-red-400') }}">
                                {{ $m['skor'] }}
                            </span>
                            {{-- Target (25) --}}
                            <span class="text-xl font-bold text-gray-700">/ 25</span>
                        </div>
                        <p class="text-[9px] font-black text-gray-600 uppercase mt-2 tracking-widest">Poin Tercapai</p>
                    </div>
                </div>

                {{-- Progress Mini --}}
                <div class="mt-8 w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-1000 {{ $m['skor'] >= 20 ? 'bg-[#8BC53F]' : 'bg-amber-500' }}" 
                         style="width: {{ ($m['skor'] / 25) * 100 }}%"></div>
                </div>
                
                <div class="mt-6 flex justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-[9px] font-black text-[#8BC53F] uppercase tracking-[0.2em]">Klik Untuk Detail Harian <i class="ph ph-arrow-right ml-1"></i></span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Info Card --}}
    <div class="mt-12 bg-white p-8 rounded-[40px] border border-gray-100 flex items-start space-x-6">
        <div class="w-12 h-12 bg-[#1B763B]/10 rounded-2xl flex items-center justify-center text-[#1B763B]">
            <i class="ph ph-info text-2xl"></i>
        </div>
        <div>
            <h4 class="font-bold text-[#473829] mb-1 text-sm">Informasi Bobot Nilai</h4>
            <p class="text-xs text-gray-400 leading-relaxed">
                Poin setiap minggu dihitung berdasarkan kehadiran di 13 kegiatan wajib harian. 
                Jika santri hadir penuh selama 7 hari, maka otomatis mendapatkan poin maksimal <b>25.0</b>. 
                Poin akan berkurang secara otomatis jika terdapat status <b>ALPHA</b> pada salah satu kegiatan.
            </p>
        </div>
    </div>
</div>
@endsection