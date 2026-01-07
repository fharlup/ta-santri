@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <div class="flex items-center space-x-4 mb-10">
        <div class="w-2 h-10 bg-[#1B763B] rounded-full"></div>
        <h1 class="font-berkshire text-4xl text-[#473829]">Pendaftaran Santriwati</h1>
    </div>

    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#1B763B] p-12">
        <form action="{{ route('santri.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Nama Lengkap --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" placeholder="CONTOH: AISYAH HUMAIRA" required 
                        class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none font-bold text-[#473829] transition uppercase">
                </div>

                {{-- Username --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Username (Untuk Login)</label>
                    <div class="relative">
                        <i class="ph ph-user absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 text-xl"></i>
                        <input type="text" name="username" placeholder="aisyah2024" required 
                            class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl pl-14 pr-6 py-4 focus:border-[#1B763B] outline-none font-bold text-[#473829] transition">
                    </div>
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Password</label>
                    <div class="relative">
                        <i class="ph ph-key-hole absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 text-xl"></i>
                        <input type="password" name="password" placeholder="••••••••" required 
                            class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl pl-14 pr-6 py-4 focus:border-[#1B763B] outline-none font-bold text-[#473829] transition">
                    </div>
                </div>
                <div class="space-y-2">
    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">NIM / Nomor Induk</label>
    <input type="text" name="nim" placeholder="Contoh: 12345" required 
        class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none font-bold">
</div>

                {{-- RFID ID --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">ID Kartu (RFID ID)</label>
                    <div class="relative">
                        <i class="ph ph-barcode absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 text-xl"></i>
                        <input type="text" name="rfid_id" placeholder="Tap kartu..." required 
                            class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl pl-14 pr-6 py-4 focus:border-[#1B763B] outline-none font-mono font-bold text-[#473829] transition">
                    </div>
                </div>

                {{-- Angkatan & Kelas (Row) --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Angkatan</label>
                        <select name="angkatan" required class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none font-bold text-[#473829] appearance-none transition">
                            <option value="">-- Pilih --</option>
                            @foreach($angkatans as $a)
                                <option value="{{ $a->nama_angkatan }}">{{ $a->nama_angkatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Kelas</label>
                        <select name="kelas" required class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none font-bold text-[#473829] appearance-none transition">
                            <option value="">-- Pilih --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-10">
                <a href="{{ route('santri.index') }}" class="px-8 py-4 text-gray-400 font-bold uppercase text-xs tracking-widest hover:text-red-500 transition">Batal</a>
                <button type="submit" class="bg-[#1B763B] text-white px-12 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl hover:bg-[#473829] transition transform hover:-translate-y-1">
                    <i class="ph ph-user-plus mr-2"></i> Daftarkan Santriwati
                </button>
            </div>
        </form>
    </div>
</div>
@endsection