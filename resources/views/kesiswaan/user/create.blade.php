@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="font-berkshire text-5xl text-[#473829] mb-8">Tambah Staff</h1>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#1B763B] p-12">
        <form action="{{ route('user.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829]">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required class="col-span-9 border-2 border-gray-100 rounded-2xl px-6 py-4 outline-none focus:border-[#1B763B]">
            </div>

            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829]">Username</label>
                <input type="text" name="username" required class="col-span-9 border-2 border-gray-100 rounded-2xl px-6 py-4 outline-none focus:border-[#1B763B]">
            </div>

            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829]">Password</label>
                <input type="password" name="password" required class="col-span-9 border-2 border-gray-100 rounded-2xl px-6 py-4 outline-none focus:border-[#1B763B]">
            </div>

            <div class="grid grid-cols-12 gap-6 items-center">
                <label class="col-span-3 font-bold text-[#473829]">Role</label>
                <select name="role" required class="col-span-9 border-2 border-gray-100 rounded-2xl px-6 py-4 outline-none focus:border-[#1B763B]">
                    <option value="Kesiswaan">Kesiswaan</option>
                    <option value="Komdis">Komdis</option>
                    <option value="Musyrifah">Musyrifah</option>
                </select>
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit" class="bg-[#1B763B] text-white px-12 py-4 rounded-2xl font-bold shadow-lg hover:bg-[#473829] transition">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection