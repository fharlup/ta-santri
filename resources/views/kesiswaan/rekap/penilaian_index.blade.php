@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10 px-4 sm:px-6 lg:px-8">
    {{-- Header & Navigasi --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <a href="{{ route('rekap.bulanan', [$santri->id, $bulan, $tahun]) }}" 
           class="flex items-center text-gray-400 hover:text-[#1B763B] transition font-bold text-[10px] uppercase tracking-widest">
            <i class="ph ph-arrow-left mr-2 text-xl"></i> Kembali ke Rekap Bulanan
        </a>
        <div class="bg-white px-6 py-3 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <i class="ph-duotone ph-calendar text-2xl text-[#1B763B]"></i>
            <div>
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Periode Laporan</p>
                <p class="text-xs font-bold text-[#473829] uppercase">
                    {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
                </p>
            </div>
        </div>
    </div>

    {{-- Matriks Card --}}
    <div class="bg-white p-8 md:p-14 rounded-[50px] shadow-sm border border-gray-100 relative overflow-hidden">
        {{-- Identitas Santriwati --}}
        <div class="relative z-10 mb-16 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="font-berkshire text-5xl text-[#473829] mb-2">Index Nilai Karakter</h1>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-[#1B763B]/10 text-[#1B763B] text-[10px] font-black rounded-lg uppercase tracking-widest">
                        {{ $santri->angkatan }}
                    </span>
                    <p class="text-sm font-bold text-gray-500">{{ $santri->nama_lengkap }}</p>
                </div>
            </div>
            <button onclick="window.print()" class="p-4 bg-gray-50 text-[#473829] rounded-2xl hover:bg-[#1B763B] hover:text-white transition shadow-inner">
                <i class="ph ph-printer text-2xl"></i>
            </button>
        </div>

        {{-- Tabel Matriks A-E --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-50">
                        <th class="py-6 text-left font-black text-gray-400 uppercase text-[10px] tracking-[0.2em] w-1/3">Aspek Penilaian</th>
                        @foreach(['A', 'B', 'C', 'D', 'E'] as $grade)
                            <th class="py-6 text-center font-black text-gray-400 uppercase text-[10px] tracking-[0.2em]">{{ $grade }}</th>
                        @endforeach
                    </tr>
                </thead>
               <tbody class="divide-y divide-gray-50">
    @foreach($aspekPenilaian as $label => $column)
    @php
        // Mengambil nilai kolom secara dinamis (misal: $penilaian->disiplin)
        $nilaiAktif = $penilaian ? strtoupper($penilaian->$column) : null;
    @endphp
    <tr class="group hover:bg-gray-50/50 transition">
        <td class="py-6">
            <p class="font-black text-[#473829] uppercase text-[11px] tracking-tight group-hover:text-[#1B763B] transition-colors">
                {{ $label }}
            </p>
        </td>
        
        @foreach(['A', 'B', 'C', 'D', 'E'] as $g)
        <td class="py-6 text-center">
            @if($nilaiAktif == $g)
                {{-- Lingkaran Aktif Sesuai Sketsa image_0e2176.png --}}
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full border-4 border-[#1B763B] bg-[#1B763B]/10 text-[#1B763B] font-black text-sm shadow-md">
                    {{ $g }}
                </div>
            @else
                <div class="inline-block w-6 h-6 rounded-full bg-gray-100 border border-gray-200 opacity-20"></div>
            @endif
        </td>
        @endforeach
    </tr>
    @endforeach
</tbody>
            </table>
        </div>

        {{-- Footer Laporan --}}
        <div class="mt-20 grid grid-cols-1 md:grid-cols-2 gap-12 items-end">
            <div class="p-8 bg-gray-50 rounded-[30px] border border-gray-100">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">
                    * Data ini dihasilkan secara otomatis oleh sistem SI-DISIPLIN berdasarkan input harian musyrif/musyrifah. 
                    Nilai indeks mencerminkan konsistensi santriwati dalam menjalankan aturan pondok.
                </p>
            </div>
            <div class="text-center px-10">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mb-20">Kepala Kesiswaan</p>
                <div class="w-full border-b-2 border-[#473829] mb-2 opacity-20"></div>
                <p class="text-[10px] font-black text-[#473829] uppercase tracking-widest">Tunas Qur'an Digital Signature</p>
            </div>
        </div>
    </div>
</div>
@endsection