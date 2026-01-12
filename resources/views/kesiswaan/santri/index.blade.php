    @extends('layouts.app')

    @section('content')
    {{-- SEARCH & FILTER SECTION --}}
<div class="mb-8 bg-white p-6 rounded-[30px] shadow-sm border border-gray-100">
    <form action="{{ route('santri.index') }}" method="GET" class="flex flex-wrap items-end gap-6">
        {{-- SEARCH NAMA --}}
        <div class="flex-[2] min-w-[300px]">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Cari Nama Santriwati</label>
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama lengkap santri..." 
                       class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-5 py-3.5 font-bold text-[#473829] outline-none focus:ring-2 focus:ring-[#1B763B] transition">
            </div>
        </div>

        {{-- FILTER ANGKATAN --}}
        <div class="flex-1 min-w-[150px]">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Angkatan</label>
            <select name="angkatan" onchange="this.form.submit()" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3.5 font-bold text-[#473829] outline-none">
                <option value="">Semua</option>
                @foreach($allAngkatan as $a)
                    <option value="{{ $a }}" {{ request('angkatan') == $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>

        {{-- Tombol Cari --}}
        <button type="submit" class="bg-[#1B763B] text-white px-8 py-4 rounded-2xl font-black text-xs uppercase shadow-lg hover:bg-[#473829] transition">
            Cari
        </button>
        
        {{-- Reset --}}
        @if(request('search') || request('angkatan'))
            <a href="{{ route('santri.index') }}" class="text-xs font-bold text-red-400 hover:text-red-600 uppercase underline decoration-2 underline-offset-4 mb-4">Reset</a>
        @endif
    </form>
</div>
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