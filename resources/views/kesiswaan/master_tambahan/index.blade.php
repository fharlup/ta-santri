@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-10">
    
    {{-- KOLOM ANGKATAN --}}
    <div class="space-y-6">
        <h2 class="font-berkshire text-3xl text-[#473829]">Master Angkatan</h2>
        <div class="bg-white p-8 rounded-[30px] shadow-xl border-t-8 border-[#1B763B]">
            <form action="{{ route('master.angkatan.store') }}" method="POST" class="flex gap-4 mb-6">
                @csrf
                <input type="text" name="nama_angkatan" placeholder="Contoh: 2024" required class="flex-1 border-2 bg-gray-50 rounded-xl px-4 py-2 outline-none focus:border-[#1B763B]">
                <button type="submit" class="bg-[#1B763B] text-white px-6 py-2 rounded-xl font-bold">+ Tambah</button>
            </form>
            <ul class="space-y-3">
                @foreach($angkatans as $a)
                <li class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl">
                    <span class="font-bold text-[#473829]">{{ $a->nama_angkatan }}</span>
                    <form action="{{ route('master.angkatan.destroy', $a->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600"><i class="ph ph-trash"></i></button>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- KOLOM KELAS --}}
    <div class="space-y-6">
        <h2 class="font-berkshire text-3xl text-[#473829]">Master Kelas</h2>
        <div class="bg-white p-8 rounded-[30px] shadow-xl border-t-8 border-[#473829]">
            <form action="{{ route('master.kelas.store') }}" method="POST" class="flex gap-4 mb-6">
                @csrf
                <input type="text" name="nama_kelas" placeholder="Contoh: 7A" required class="flex-1 border-2 bg-gray-50 rounded-xl px-4 py-2 outline-none focus:border-[#473829]">
                <button type="submit" class="bg-[#473829] text-white px-6 py-2 rounded-xl font-bold">+ Tambah</button>
            </form>
            <ul class="space-y-3">
                @foreach($kelas as $k)
                <li class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl">
                    <span class="font-bold text-[#473829]">{{ $k->nama_kelas }}</span>
                    <form action="{{ route('master.kelas.destroy', $k->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600"><i class="ph ph-trash"></i></button>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection