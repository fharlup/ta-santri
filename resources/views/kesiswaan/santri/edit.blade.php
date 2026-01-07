@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <div class="flex items-center space-x-4 mb-10">
        <div class="w-2 h-10 bg-[#473829] rounded-full"></div>
        <h1 class="font-berkshire text-4xl text-[#473829]">Edit Data Santriwati</h1>
    </div>

    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#473829] p-12">
        <form action="{{ route('santri.update', $santri->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Nama Lengkap --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ $santri->nama_lengkap }}" required 
                        class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#473829] outline-none font-bold text-[#473829] transition uppercase">
                </div>

                {{-- Username --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Username</label>
                    <input type="text" name="username" value="{{ $santri->username }}" required 
                        class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#473829] outline-none font-bold text-[#473829] transition">
                </div>

                {{-- Password (Opsional) --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Ganti Password <span class="text-[8px] italic text-red-400 font-bold normal-case ml-2">(Kosongkan jika tidak diganti)</span></label>
                    <input type="password" name="password" placeholder="••••••••" 
                        class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#473829] outline-none font-bold text-[#473829] transition">
                </div>

                {{-- RFID ID --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">ID Kartu (RFID ID)</label>
                    <input type="text" name="rfid_id" value="{{ $santri->rfid_id }}" required 
                        class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#473829] outline-none font-mono font-bold text-[#473829] transition">
                </div>

                {{-- Angkatan & Kelas (Dropdown selected) --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Angkatan</label>
                        <select name="angkatan" required class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#473829] outline-none font-bold text-[#473829] transition">
                            @foreach($angkatans as $a)
                                <option value="{{ $a->nama_angkatan }}" {{ $santri->angkatan == $a->nama_angkatan ? 'selected' : '' }}>
                                    {{ $a->nama_angkatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Kelas</label>
                        <select name="kelas" required class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#473829] outline-none font-bold text-[#473829] transition">
                            @foreach($kelas as $k)
                                <option value="{{ $k->nama_kelas }}" {{ $santri->kelas == $k->nama_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-10">
                <a href="{{ route('santri.index') }}" class="px-8 py-4 text-gray-400 font-bold uppercase text-xs tracking-widest hover:text-red-500 transition">Batal</a>
                <button type="submit" class="bg-[#473829] text-white px-12 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl hover:bg-[#1B763B] transition transform hover:-translate-y-1">
                    <i class="ph ph-check-circle mr-2"></i> Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection