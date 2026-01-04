@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="font-berkshire text-5xl text-[#473829]">Data Santriwati</h1>
        <a href="{{ route('santri.create') }}" 
           class="bg-[#1B763B] text-white px-8 py-3 rounded-2xl font-bold shadow-xl hover:bg-[#473829] transition transform hover:-translate-y-1 flex items-center">
           <i class="ph ph-plus-circle mr-2 text-xl"></i> Tambah Santri
        </a>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#1B763B] overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/50">
            <h3 class="font-bold text-[#473829]">Daftar Seluruh Santriwati</h3>
        </div>
        
        <table class="w-full text-left">
            <thead>
                <tr class="text-[#473829] font-bold text-sm uppercase tracking-wider bg-gray-50">
                    <th class="px-8 py-5">No</th>
                    <th class="px-8 py-5">Identitas Santri</th>
                    <th class="px-8 py-5">NIM</th>
                    <th class="px-8 py-5">Kelas</th>
                    <th class="px-8 py-5">Akun Login</th>
                    <th class="px-8 py-5">RFID</th>
                    <th class="px-8 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($santris as $index => $santri)
                <tr class="hover:bg-green-50/50 transition">
                    <td class="px-8 py-6 font-bold text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-8 py-6">
                        <p class="font-bold text-[#473829] text-base">{{ $santri->nama_lengkap }}</p>
                    </td>
                    <td class="px-8 py-6 text-gray-500 font-mono">{{ $santri->nim }}</td>
                    <td class="px-8 py-6">
                        <span class="bg-[#8BC53F]/10 text-[#1B763B] px-4 py-1 rounded-full font-bold text-xs">
                            {{ $santri->kelas }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <p class="text-[#1B763B] font-bold">{{ $santri->username }}</p>
                        <p class="text-[10px] text-gray-400 italic">Pass: Encrypted</p>
                    </td>
                    <td class="px-8 py-6 font-mono text-gray-400 text-xs">{{ $santri->rfid_id }}</td>
                    <td class="px-8 py-6">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('santri.edit', $santri->id) }}" 
                               class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </a>
                            <form action="{{ route('santri.destroy', $santri->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection