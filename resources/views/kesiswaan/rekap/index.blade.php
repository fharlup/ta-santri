@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-10 flex items-center space-x-5">
        <div class="w-3 h-14 bg-[#1B763B] rounded-full shadow-lg"></div>
        <div>
            <h1 class="font-berkshire text-4xl text-[#473829]">Pilih Santriwati</h1>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Pilih anak untuk melihat rekapitulasi poin harian, mingguan, dan bulanan</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="mb-10 bg-white p-6 rounded-[30px] shadow-sm border border-gray-100">
        <form action="{{ route('rekap.index') }}" method="GET" class="flex flex-wrap items-end gap-6">
            <div class="flex-1 min-w-[300px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Cari Nama</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama santriwati..." 
                       class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
            </div>

            <div class="w-[200px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Angkatan</label>
                <select name="angkatan" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
                    <option value="">Semua</option>
                    @foreach($allAngkatan as $a)
                        <option value="{{ $a->nama_angkatan }}" {{ request('angkatan') == $a->nama_angkatan ? 'selected' : '' }}>{{ $a->nama_angkatan }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-[#1B763B] text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-[#473829] transition transform active:scale-95">
                Cari Data
            </button>
        </form>
    </div>

    {{-- Grid Daftar Anak --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($santris as $s)
        <a href="{{ route('rekap.tahunan', $s->id) }}" class="group">
            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100 hover:border-[#1B763B] transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                <div class="w-20 h-20 bg-gray-100 rounded-3xl mb-6 flex items-center justify-center text-gray-300 group-hover:bg-[#1B763B]/10 group-hover:text-[#1B763B] transition-colors">
                    <i class="ph-duotone ph-user-circle text-5xl"></i>
                </div>
                
                <h3 class="font-black text-[#473829] uppercase text-sm leading-tight mb-2 group-hover:text-[#1B763B] transition-colors">
                    {{ $s->nama_lengkap }}
                </h3>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Angkatan {{ $s->angkatan }}
                    </span>
                    <i class="ph ph-arrow-right text-[#1B763B] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-20 text-center">
            <p class="font-bold text-gray-300 uppercase tracking-widest">Santriwati tidak ditemukan.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $santris->links() }}
    </div>
</div>
@endsection