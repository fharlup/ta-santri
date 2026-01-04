@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="font-berkshire text-5xl text-[#473829]">Jadwal Kegiatan</h1>
        <a href="{{ route('kegiatan.create') }}" class="bg-[#1B763B] text-white px-8 py-3 rounded-2xl font-bold shadow-xl hover:bg-[#473829] transition">
           + Tambah Kegiatan
        </a>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#1B763B] overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[#473829] font-bold text-sm bg-gray-50">
                    <th class="px-8 py-5">No</th>
                    <th class="px-8 py-5">Nama Kegiatan</th>
                    <th class="px-8 py-5">Jam Pelaksanaan</th>
                    <th class="px-8 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($kegiatans as $index => $k)
                <tr class="hover:bg-green-50/50 transition">
                    <td class="px-8 py-6 font-bold text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-8 py-6 font-bold text-[#473829]">{{ $k->nama_kegiatan }}</td>
                    <td class="px-8 py-6 text-[#1B763B] font-bold">{{ \Carbon\Carbon::parse($k->jam)->format('H:i') }} WIB</td>
                    <td class="px-8 py-6 text-center">
                        <a href="{{ route('kegiatan.edit', $k->id) }}" class="text-blue-600 font-bold hover:underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection