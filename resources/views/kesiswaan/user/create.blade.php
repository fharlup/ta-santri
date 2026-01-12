@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <div class="flex items-center space-x-4 mb-10">
        <div class="w-2 h-10 bg-[#1B763B] rounded-full"></div>
        <h1 class="font-berkshire text-4xl text-[#473829]">Tambah Pengguna / Staf</h1>
    </div>

    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#1B763B] p-12">
        <form action="{{ route('user.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Nama Staf --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Nama Lengkap Staf</label>
                    <input type="text" name="nama_lengkap" placeholder="Nama Lengkap & Gelar" required 
                        class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none font-bold text-[#473829]">
                </div>

                {{-- Username --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Username</label>
                    <input type="text" name="username" placeholder="Contoh: ustadzah.nina" required 
                        class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none font-bold text-[#473829]">
                </div>

                {{-- Role --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Jabatan / Hak Akses</label>
                    <select name="role" required class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none font-bold text-[#473829]">
                        <option value="Musyrifah">Musyrifah (Pendamping)</option>
                        <option value="Komdis">Komdis (Kedisiplinan)</option>
                        <option value="Kesiswaan">Kesiswaan (Administrator)</option>
                    </select>
                </div>

                {{-- Password --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Password Akun</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" required 
                        class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none font-bold text-[#473829]">
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-10">
                <a href="{{ route('user.index') }}" class="px-8 py-4 text-gray-400 font-bold uppercase text-xs tracking-widest">Batal</a>
                <button type="submit" class="bg-[#1B763B] text-white px-12 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl hover:bg-[#473829] transition">
                    Simpan Staf Baru
                </button>
            </div>
        </form>
    </div>
</div>
@if ($errors->any())
    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-xl font-bold">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 p-4 bg-red-500 text-white rounded-xl font-bold shadow-lg">
        ⚠️ {{ session('error') }}
    </div>
@endif
@endsection