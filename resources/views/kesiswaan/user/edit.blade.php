@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="font-berkshire text-5xl text-[#473829]">Edit Staff</h1>
        <a href="{{ route('user.index') }}" class="text-[#1B763B] font-bold hover:underline">← Kembali</a>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#473829] p-12">
        <form action="{{ route('user.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829] text-lg">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required 
                    class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#473829] outline-none transition">
            </div>

            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829] text-lg">Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required 
                    class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 focus:border-[#473829] outline-none transition">
            </div>

            <div class="grid grid-cols-12 gap-6 items-start">
                <label class="col-span-3 font-bold text-[#1B763B] text-lg pt-4">Password Baru</label>
                <div class="col-span-9">
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti password" 
                        class="w-full border-2 border-[#1B763B]/20 bg-[#1B763B]/5 rounded-2xl px-6 py-4 focus:border-[#1B763B] outline-none transition italic">
                    <p class="text-[10px] text-gray-400 mt-2">*Minimal 6 karakter jika ingin diubah.</p>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829] text-lg">Role / Jabatan</label>
                <select name="role" required class="col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-4 outline-none appearance-none focus:border-[#473829]">
                    <option value="Kesiswaan" {{ $user->role == 'Kesiswaan' ? 'selected' : '' }}>Kesiswaan</option>
                    <option value="Komdis" {{ $user->role == 'Komdis' ? 'selected' : '' }}>Komdis</option>
                    <option value="Musyrifah" {{ $user->role == 'Musyrifah' ? 'selected' : '' }}>Musyrifah</option>
                </select>
            </div>

            <div class="flex justify-end pt-8">
                <button type="submit" class="bg-[#473829] text-white px-16 py-4 rounded-2xl font-bold shadow-xl hover:bg-[#1B763B] transition transform hover:-translate-y-1">
                    Update Data Staff
                </button>
            </div>
        </form>
    </div>
</div>
@endsection