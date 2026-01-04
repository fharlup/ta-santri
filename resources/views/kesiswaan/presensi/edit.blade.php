@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="font-berkshire text-5xl text-[#473829] mb-10">Edit Status Presensi</h1>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#473829] p-12">
        <form action="{{ route('presensi.update', $presensi->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT') <div class="grid grid-cols-12 gap-6 items-center border-b pb-4">
                <label class="col-span-3 font-bold text-[#473829]">Nama Santri</label>
                <div class="col-span-9 font-bold text-[#1B763B]">{{ $presensi->santriwati->nama_lengkap }}</div>
            </div>

            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829]">Nama Kegiatan</label>
                <select name="kegiatan_id" class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 outline-none focus:border-[#473829]">
                    @foreach($kegiatans as $k)
                        <option value="{{ $k->id }}" {{ $presensi->kegiatan_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kegiatan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829]">Status</label>
                <select name="status" class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 outline-none focus:border-[#473829] font-bold">
                    <option value="Tepat Waktu" {{ $presensi->status == 'Tepat Waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                    <option value="Terlambat" {{ $presensi->status == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>

            <div class="grid grid-cols-12 gap-6 items-start">
                <label class="col-span-3 font-bold text-[#473829] pt-4">Keterangan</label>
                <textarea name="keterangan" rows="5" class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 outline-none focus:border-[#473829]">{{ $presensi->keterangan }}</textarea>
            </div>

            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('presensi.riwayat') }}" class="px-8 py-4 text-gray-400 font-bold">Batal</a>
                <button type="submit" class="bg-[#473829] text-white px-12 py-4 rounded-2xl font-bold shadow-lg hover:bg-[#1B763B] transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection