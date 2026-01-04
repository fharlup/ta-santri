@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="font-berkshire text-5xl text-[#473829] mb-8 text-center">Edit Kegiatan</h1>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#473829] p-12">
        <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829] text-lg">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" required 
                    class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#473829] outline-none">
            </div>

            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829] text-lg">Waktu/Jam</label>
                <input type="time" name="jam" value="{{ \Carbon\Carbon::parse($kegiatan->jam)->format('H:i') }}" required 
                    class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#473829] outline-none">
            </div>

            <div class="flex justify-end space-x-4 pt-6">
                <button type="submit" class="bg-[#473829] text-white px-12 py-4 rounded-2xl font-bold shadow-lg hover:bg-[#1B763B] transition">
                    Perbarui Kegiatan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection