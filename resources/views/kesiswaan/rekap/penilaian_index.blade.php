@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10">
    {{-- Navigasi Balik --}}
    <div class="mb-8">
        <a href="{{ route('rekap.bulanan', [$santri->id, $bulan, $tahun]) }}" class="flex items-center text-gray-400 hover:text-[#1B763B] transition font-bold text-[10px] uppercase tracking-widest">
            <i class="ph ph-arrow-left mr-2 text-xl"></i> Kembali ke Rekap Bulanan
        </a>
    </div>

    <div class="bg-white p-12 rounded-[50px] shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h1 class="font-berkshire text-4xl text-[#473829] mb-1">Index Penilaian Karakter</h1>
                <p class="text-[10px] font-black text-[#1B763B] uppercase tracking-[0.2em]">
                    {{ $santri->nama_lengkap }} • {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
                </p>
            </div>
            <div class="px-6 py-3 bg-gray-50 rounded-2xl border border-gray-100">
                <p class="text-[9px] font-black text-gray-400 uppercase mb-1">ID Santriwati</p>
                <p class="text-xs font-bold text-[#473829]">{{ $santri->nim }}</p>
            </div>
        </div>

        {{-- Matriks A-E (Sesuai Sketsa Ustadz) --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-50">
                        <th class="py-6 text-left font-black text-gray-400 uppercase text-[10px] tracking-[0.2em] w-1/3">Aspek Penilaian</th>
                        @foreach(['A', 'B', 'C', 'D', 'E'] as $grade)
                            <th class="py-6 text-center font-black text-gray-400 uppercase text-[10px] tracking-[0.2em]">{{ $grade }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($aspekPenilaian as $aspek)
                    @php
                        // Cari nilai untuk aspek ini
                        $p = $dataPenilaian->where('kategori', $aspek)->first();
                        $nilai = $p ? strtoupper($p->nilai) : null;
                    @endphp
                    <tr>
                        <td class="py-8 font-bold text-[#473829] uppercase text-sm tracking-tight">{{ $aspek }}</td>
                        
                        @foreach(['A', 'B', 'C', 'D', 'E'] as $g)
                        <td class="py-8 text-center">
                            @if($nilai == $g)
                                {{-- Lingkaran Penanda seperti di image_0e2176.png --}}
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full border-4 border-[#1B763B] bg-[#1B763B]/10 text-[#1B763B] font-black shadow-lg">
                                    {{ $g }}
                                </div>
                            @else
                                <div class="inline-block w-6 h-6 rounded-full bg-gray-50 border border-gray-100"></div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer Laporan --}}
        <div class="mt-16 pt-8 border-t border-gray-50 flex justify-between items-end">
            <div class="max-w-md">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">
                    Keterangan: Indikator penilaian ini bersifat akumulatif selama satu bulan. 
                    Setiap lingkaran menandakan pencapaian santriwati pada aspek terkait.
                </p>
            </div>
            <div class="text-center border-t border-gray-200 pt-4 px-10">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-12">Pembimbing Kamar</p>
                <p class="text-xs font-bold text-[#473829]">( ____________________ )</p>
            </div>
        </div>
    </div>
</div>
@endsection