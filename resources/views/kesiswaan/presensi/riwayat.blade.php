@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <h1 class="font-berkshire text-5xl text-[#473829]">Riwayat Presensi</h1>

    <form action="{{ route('presensi.riwayat') }}" method="GET" class="flex flex-wrap gap-4 items-center bg-[#473829]/5 p-6 rounded-[30px] border border-[#473829]/10">
        <select name="status" class="bg-white border-2 border-[#473829]/20 rounded-xl px-4 py-2 text-sm outline-none focus:border-[#1B763B] text-[#473829] font-bold">
            <option value="">Semua Status</option>
            <option value="Tepat Waktu">Tepat Waktu</option>
            <option value="Terlambat">Terlambat</option>
        </select>
        <div class="flex-1">
            <input type="text" name="search" placeholder="Cari Nama Santriwati..." value="{{ request('search') }}"
                   class="w-full bg-white border-2 border-[#473829]/20 rounded-xl px-4 py-2 text-sm outline-none focus:border-[#1B763B]">
        </div>
        <button type="submit" class="bg-[#1B763B] text-white px-8 py-2 rounded-xl font-bold shadow-lg hover:bg-[#473829] transition">Filter</button>
    </form>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#473829] overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-[#473829]/5">
                <tr class="text-[#473829] font-bold text-sm uppercase tracking-wider">
                    <th class="px-8 py-5">Tanggal</th>
                    <th class="px-8 py-5">Nama Santri</th>
                    <th class="px-8 py-5">Kelas</th>
                    <th class="px-8 py-5">Kegiatan</th>
                    <th class="px-8 py-5">Status</th>
                    <th class="px-8 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($presensis as $p)
                <tr class="hover:bg-green-50/30 transition">
                    <td class="px-8 py-6 text-gray-500 font-bold">{{ $p->waktu_scan->format('d/m/y') }}</td>
                    <td class="px-8 py-6 font-bold text-[#473829]">{{ $p->santriwati->nama_lengkap }}</td>
                    <td class="px-8 py-6 text-gray-600">{{ $p->santriwati->kelas }}</td>
                    <td class="px-8 py-6 font-bold text-[#1B763B]">{{ $p->kegiatan->nama_kegiatan }}</td>
                    <td class="px-8 py-6">
                        <span class="px-4 py-1 rounded-full text-xs font-black {{ $p->status == 'Tepat Waktu' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <a href="{{ route('presensi.edit', $p->id) }}" class="text-[#473829] font-black hover:text-[#1B763B] hover:underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection