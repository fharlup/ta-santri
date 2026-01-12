@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="font-berkshire text-5xl text-[#473829]">Manajemen Pengguna</h1>
        <a href="{{ route('user.create') }}" class="bg-[#1B763B] text-white px-8 py-3 rounded-2xl font-bold shadow-xl hover:bg-[#473829] transition">
           + Tambah Staff
        </a>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#1B763B] overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[#473829] font-bold text-sm bg-gray-50">
                    <th class="px-8 py-5">No</th>
                    <th class="px-8 py-5">Nama Lengkap</th>
                    <th class="px-8 py-5">Username</th>
                    <th class="px-8 py-5">Role</th>
                    <th class="px-8 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $index => $u)
                <tr class="hover:bg-green-50/50 transition">
                    <td class="px-8 py-6 font-bold text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-8 py-6 font-bold text-[#473829]">{{ $u->nama_lengkap }}</td>
                    <td class="px-8 py-6 text-[#1B763B] font-bold">{{ $u->username }}</td>
                    <td class="px-8 py-6 uppercase text-xs font-black text-gray-500">{{ $u->role }}</td>
                    <td class="px-8 py-6 text-center">
                        <a href="{{ route('user.edit', $u->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition">Edit</a>
                        <form action="{{ route('user.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-400 hover:text-red-600 transition">
                <i class="ph ph-trash text-xl"></i>
            </button>
        </form>
                    </td>
                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection