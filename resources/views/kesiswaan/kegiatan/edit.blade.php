@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <h1 class="font-berkshire text-5xl text-[#473829] mb-8 text-center">Edit Jadwal</h1>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#473829] p-12">
        <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            {{-- Nama Kegiatan --}}
            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829] text-lg">Kegiatan</label>
                <input type="text" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" required 
                    class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none font-bold uppercase">
            </div>

            {{-- Jam --}}
            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829] text-lg">Jam Mulai</label>
                <input type="time" name="jam" value="{{ \Carbon\Carbon::parse($kegiatan->jam)->format('H:i') }}" required 
                    class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none font-bold text-2xl">
            </div>

            {{-- Tanggal (Opsional) --}}
            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-gray-400 text-lg">Tanggal <span class="text-[10px] block opacity-50">(Opsional)</span></label>
                <input type="date" name="tanggal" value="{{ $kegiatan->tanggal ? $kegiatan->tanggal->format('Y-m-d') : '' }}"
                    class="col-span-9 border-2 border-gray-50 bg-gray-50/50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none">
            </div>

            <hr class="border-gray-100 my-6">

            {{-- Ustadzah (Opsional) --}}
            <div class="space-y-4">
                <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Pendamping</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="ustadzah_1" value="{{ $kegiatan->ustadzah_1 }}" placeholder="Ustadzah 1" 
                        class="border-2 border-gray-100 bg-gray-50 rounded-xl px-4 py-3 outline-none focus:border-[#1B763B]">
                    <input type="text" name="ustadzah_2" value="{{ $kegiatan->ustadzah_2 }}" placeholder="Ustadzah 2" 
                        class="border-2 border-gray-100 bg-gray-50 rounded-xl px-4 py-3 outline-none focus:border-[#1B763B]">
                    <input type="text" name="ustadzah_3" value="{{ $kegiatan->ustadzah_3 }}" placeholder="Ustadzah 3" 
                        class="border-2 border-gray-100 bg-gray-50 rounded-xl px-4 py-3 outline-none focus:border-[#1B763B]">
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-10">
                <a href="{{ route('kegiatan.index') }}" class="px-8 py-4 text-gray-400 font-bold uppercase text-xs">Batal</a>
                <button type="submit" class="bg-[#473829] text-white px-12 py-4 rounded-2xl font-black uppercase text-xs shadow-xl hover:bg-[#1B763B] transition">
                    Perbarui Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection