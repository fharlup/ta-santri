@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="font-berkshire text-4xl text-[#473829]">Manajemen Santriwati</h1>
        <p class="text-gray-500">Silakan lengkapi data santriwati dan akun akses sistem.</p>
    </div>

    <div class="bg-white rounded-[35px] shadow-sm border-t-8 border-[#1B763B] p-12">
        <h2 class="font-berkshire text-2xl text-[#473829] mb-10 text-center md:text-left">Tambah Santriwati</h2>

        <form action="{{ route('santri.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-12 gap-4 items-center">
                <label class="col-span-12 md:col-span-3 text-lg font-bold text-[#473829]">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required 
                    class="col-span-12 md:col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-3 focus:border-[#1B763B] focus:bg-white outline-none transition">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <label class="col-span-12 md:col-span-4 text-lg font-bold text-[#473829]">NIM</label>
                    <input type="text" name="nim" required class="col-span-12 md:col-span-8 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-3">
                </div>
                <div class="grid grid-cols-12 gap-4 items-center">
                    <label class="col-span-12 md:col-span-4 text-lg font-bold text-[#1B763B] text-right pr-2">Username</label>
                    <input type="text" name="username" required class="col-span-12 md:col-span-8 border-2 border-[#1B763B]/20 bg-[#1B763B]/5 rounded-2xl px-6 py-3">
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 items-center">
                <label class="col-span-12 md:col-span-3 text-lg font-bold text-[#1B763B]">Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter"
                    class="col-span-12 md:col-span-9 border-2 border-[#1B763B]/20 bg-[#1B763B]/5 rounded-2xl px-6 py-3 outline-none">
            </div>

            <div class="grid grid-cols-12 gap-4 items-center">
                <label class="col-span-12 md:col-span-3 text-lg font-bold text-[#473829]">Kelas</label>
                <div class="col-span-12 md:col-span-9 relative">
                    <select name="kelas" required class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-3 outline-none appearance-none">
                        <option value="">-- Pilih kelas --</option>
                        <option value="10-A">10-A</option>
                        <option value="11-B">11-B</option>
                    </select>
                    <div class="absolute right-6 top-4 pointer-events-none text-gray-400">▼</div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 items-center">
                <label class="col-span-12 md:col-span-3 text-lg font-bold text-[#473829]">RFid</label>
                <input type="text" name="rfid_id" placeholder="Input Id Kartu" required
                    class="col-span-12 md:col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-3 italic outline-none">
            </div>

            <div class="flex justify-end space-x-4 pt-10">
                <a href="{{ route('kesiswaan.dashboard') }}" 
                   class="px-10 py-3 text-gray-400 font-bold hover:text-[#473829] transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-12 py-3 bg-[#1B763B] text-white rounded-2xl font-bold shadow-lg shadow-green-900/20 hover:bg-[#473829] transition transform hover:-translate-y-1">
                    Simpan Santriwati
                </button>
            </div>
        </form>
    </div>
</div>
@endsection