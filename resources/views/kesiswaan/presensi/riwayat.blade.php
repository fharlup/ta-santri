@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10 px-4 sm:px-6 lg:px-8">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
        <div class="flex items-center space-x-5">
            <div class="w-3 h-14 bg-[#1B763B] rounded-full shadow-lg"></div>
            <div>
                <h1 class="font-berkshire text-4xl text-[#473829]">Riwayat Presensi</h1>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Monitoring Kehadiran & Kedisiplinan Santriwati</p>
            </div>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('presensi.export', request()->all()) }}" class="px-6 py-4 bg-[#8BC53F] text-white rounded-2xl font-black text-xs uppercase shadow-md hover:bg-[#1B763B] transition">
                <i class="ph ph-file-xls mr-2 text-xl"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="mb-8 bg-white p-6 rounded-[30px] shadow-sm border border-gray-100">
        <form action="{{ route('presensi.riwayat') }}" method="GET" class="flex flex-wrap items-end gap-6">
            {{-- Filter Angkatan --}}
            <div class="flex-1 min-w-[200px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Pilih Angkatan</label>
                <select name="angkatan" onchange="this.form.submit()" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
                    <option value="">Semua Angkatan</option>
                    @foreach($allAngkatan as $a)
                        <option value="{{ $a }}" {{ request('angkatan') == $a ? 'selected' : '' }}>Angkatan {{ $a }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal --}}
            <div class="flex-1 min-w-[200px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Tanggal Presensi</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()"
                       class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
            </div>

            {{-- Tombol Cari --}}
            <button type="submit" class="bg-[#1B763B] text-white px-8 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-[#473829] transition">
                <i class="ph ph-magnifying-glass mr-2"></i> Cari
            </button>
            
            @if(request('angkatan') || request('tanggal'))
                <a href="{{ route('presensi.riwayat') }}" class="text-xs font-bold text-red-400 hover:text-red-600 uppercase underline decoration-2 underline-offset-4">Reset</a>
            @endif
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#1B763B] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b">
                        <th class="px-8 py-8 text-[10px] font-black text-gray-400 uppercase sticky left-0 bg-gray-50 z-10">Data Santri & Kegiatan</th>
                        <th class="px-4 py-8 text-[10px] font-black text-[#1B763B] uppercase text-center">Waktu Scan</th>
                        <th class="px-4 py-8 text-[10px] font-black text-[#1B763B] uppercase text-center">Status</th>
                        <th class="px-6 py-8 text-[10px] font-black text-gray-400 uppercase">Keterangan</th>
                        {{-- Kolom Aksi yang Ditambahkan Kembali --}}
                        <th class="px-6 py-8 text-[10px] font-black text-gray-400 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($presensis as $p)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-8 py-6 sticky left-0 bg-white group-hover:bg-gray-50 z-10 shadow-sm">
                            <p class="font-black text-[#473829] uppercase text-sm leading-tight">
                                {{ $p->santriwati->nama_lengkap ?? 'Unknown' }}
                            </p>
                            <span class="text-[9px] font-bold text-gray-300 uppercase italic">
                                Angkatan {{ $p->santriwati->angkatan ?? '-' }} | {{ $p->kegiatan->nama_kegiatan ?? 'Tanpa Nama Kegiatan' }}
                            </span>
                        </td>

                        <td class="px-4 py-6 text-center">
                            <p class="font-bold text-[#473829] text-sm">{{ \Carbon\Carbon::parse($p->waktu_scan)->format('H:i:s') }}</p>
                            <p class="text-[9px] text-gray-400 font-bold uppercase">{{ \Carbon\Carbon::parse($p->waktu_scan)->format('d/m/Y') }}</p>
                        </td>

                        <td class="px-4 py-6 text-center">
                            @php
                                $status = $p->status;
                                $colorClass = match($status) {
                                    'Hadir' => 'bg-green-100 text-green-600',
                                    'Terlambat' => 'bg-amber-100 text-amber-600',
                                    'Izin' => 'bg-blue-100 text-blue-600',
                                    'Sakit' => 'bg-purple-100 text-purple-600',
                                    default => 'bg-red-100 text-red-600',
                                };
                            @endphp
                            <span class="inline-block px-4 py-1.5 rounded-full font-black text-[10px] uppercase {{ $colorClass }}">
                                {{ $status }}
                            </span>
                        </td>

                        <td class="px-6 py-6">
                            <p class="text-xs text-gray-500 font-medium italic">{{ $p->keterangan ?? '-' }}</p>
                        </td>

                        {{-- Tombol Edit --}}
                        <td class="px-6 py-6 text-center">
                            <a href="{{ route('presensi.edit', $p->id) }}" class="text-[#473829] hover:text-[#1B763B] transition">
                                <i class="ph ph-note-pencil text-xl"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-gray-400 font-bold italic uppercase text-[10px]">
                            Belum ada riwayat presensi yang terekam.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $presensis->appends(request()->all())->links() }}
    </div>
</div>
@endsection