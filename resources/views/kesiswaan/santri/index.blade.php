@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="font-berkshire text-4xl text-[#473829]">Manajemen Santriwati</h1>
    </div>

    <div class="inline-block mb-4">
        <a href="{{ route('santri.create') }}" 
           class="bg-[#D9D9D9] border-2 border-[#473829]/20 px-8 py-2 rounded-xl font-bold text-[#473829] hover:bg-[#473829] hover:text-white transition">
            Tambah Santriwati
        </a>
    </div>

    <div class="bg-[#D9D9D9] rounded-[40px] p-8 shadow-sm overflow-hidden min-h-[500px]">
        <table class="w-full text-left border-separate border-spacing-y-0">
            <thead>
                <tr class="text-[#473829] font-bold text-sm">
                    <th class="px-4 py-4">No</th>
                    <th class="px-4 py-4">Nama</th>
                    <th class="px-4 py-4">NIM</th>
                    <th class="px-4 py-4">Kelas</th>
                    <th class="px-4 py-4">Username</th>
                    <th class="px-4 py-4">Password</th>
                    <th class="px-4 py-4">RFID</th>
                    <th class="px-4 py-4">Action</th>
                </tr>
            </thead>
            <tbody class="text-xs text-[#473829]">
                @foreach($santris as $index => $santri)
                <tr class="hover:bg-black/5 transition">
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 font-bold">{{ $santri->nama_lengkap }}</td>
                    <td class="px-4 py-3">{{ $santri->nim }}</td>
                    <td class="px-4 py-3">{{ $santri->kelas }}</td>
                    <td class="px-4 py-3">{{ $santri->username }}</td>
                    <td class="px-4 py-3 italic opacity-50">Encrypted</td>
                    <td class="px-4 py-3">{{ $santri->rfid_id }}</td>
                    <td class="px-4 py-3 font-bold">
                        <a href="{{ route('santri.edit', $santri->id) }}" class="hover:underline">Edit</a> 
                        <span class="px-1">|</span> 
                        <form action="{{ route('santri.destroy', $santri->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus data ini?')" class="hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection