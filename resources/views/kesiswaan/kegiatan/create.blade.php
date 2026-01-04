@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="font-berkshire text-5xl text-[#473829] mb-8 text-center">Tambah Kegiatan</h1>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#1B763B] p-12">
        <form action="{{ route('kegiatan.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829] text-lg">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" placeholder="Contoh: Sholat Dzuhur" required 
                    class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none">
            </div>

            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829] text-lg">Waktu/Jam</label>
                <input type="time" name="jam" required 
                    class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none">
            </div>

            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('kegiatan.index') }}" class="px-8 py-3 text-gray-400 font-bold">Batal</a>
                <button type="submit" class="bg-[#1B763B] text-white px-12 py-4 rounded-2xl font-bold shadow-lg hover:bg-[#473829] transition">
                    Simpan Kegiatan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection