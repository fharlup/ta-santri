@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10">
    <div class="flex items-center justify-between mb-10">
        <div class="flex items-center space-x-5">
            <div class="w-3 h-14 bg-[#1B763B] rounded-full shadow-lg"></div>
            <div>
                <h1 class="font-berkshire text-4xl text-[#473829]">Rekap Penilaian Ahlak</h1>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Data Predikat Karakter Santriwati</p>
            </div>
        </div>
        
        <div class="flex gap-4">
            {{-- Tombol Export Excel --}}
            <a href="{{ route('penilaian.export') }}" class="px-6 py-4 bg-[#8BC53F] text-white rounded-2xl font-black text-xs uppercase shadow-md hover:bg-[#1B763B] transition">
                <i class="ph ph-file-xls mr-2 text-xl"></i> Export Excel
            </a>
            <a href="{{ route('penilaian.create') }}" class="px-6 py-4 bg-[#473829] text-white rounded-2xl font-black text-xs uppercase shadow-md">
                + Input Penilaian
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#473829] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-8 py-8 text-[10px] font-black text-gray-400 uppercase sticky left-0 bg-gray-50">Nama Santriwati</th>
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
                    <tr class="hover:bg-gray-50">
                        <td class="px-8 py-6 sticky left-0 bg-white shadow-sm font-black text-[#473829]">
                            {{ $p->santriwati->nama_lengkap }}
                            <br><span class="text-[9px] text-gray-400 font-bold uppercase">{{ $p->angkatan }}</span>
                        </td>
                        
                        {{-- Menampilkan Nilai Karakter --}}
                        @foreach(['adab', 'disiplin', 'tanggung_jawab', 'integritas_kesabaran', 'integritas_kejujuran'] as $field)
                        <td class="px-4 py-6 text-center">
                            @php
                                $val = $p->$field;
                                $color = $val == 'A' ? 'bg-green-100 text-green-600' : ($val == 'C' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-500');
                            @endphp
                            <span class="inline-block w-8 h-8 leading-8 rounded-lg font-black text-xs {{ $color }}">
                                {{ $val }}
                            </span>
                        </td>
                        @endforeach

                        <td class="px-6 py-6 text-center">
                            <a href="{{ route('penilaian.edit', $p->id) }}" class="text-[#473829] hover:text-[#1B763B]">
                                <i class="ph ph-note-pencil text-xl"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-20 text-center text-gray-400 italic">Belum ada data penilaian karakter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection