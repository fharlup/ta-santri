@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="font-berkshire text-4xl text-[#473829]">Edit Santriwati</h1>
        {{-- Tombol kembali mengarah ke halaman index (daftar) --}}
        <a href="{{ route('santri.index') }}" class="text-[#1B763B] font-bold hover:underline">← Kembali ke Daftar</a>
    </div>

    <div class="bg-white rounded-[35px] shadow-sm border-t-8 border-[#1B763B] p-12">
        <h2 class="font-berkshire text-2xl text-[#473829] mb-10 text-center md:text-left">
            Perbarui Data: {{ $santri->nama_lengkap }}
        </h2>

        {{-- Perhatikan action route dan method POST --}}
        <form action="{{ route('santri.update', $santri->id) }}" method="POST" class="space-y-6">
            @csrf
            {{-- WAJIB: Method Spoofing untuk Update (PUT) --}}
            @method('PUT')
            
            <div class="grid grid-cols-12 gap-4 items-center">
                <label class="col-span-12 md:col-span-3 text-lg font-bold text-[#473829]">Nama Lengkap</label>
                {{-- value diisi dengan data lama ($santri->nama_lengkap) --}}
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $santri->nama_lengkap) }}" required 
                    class="col-span-12 md:col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-3 focus:border-[#1B763B] focus:bg-white outline-none transition">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <label class="col-span-12 md:col-span-4 text-lg font-bold text-[#473829]">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim', $santri->nim) }}" required 
                        class="col-span-12 md:col-span-8 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-3">
                </div>
                <div class="grid grid-cols-12 gap-4 items-center">
                    <label class="col-span-12 md:col-span-4 text-lg font-bold text-[#1B763B] text-right pr-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', $santri->username) }}" required 
                        class="col-span-12 md:col-span-8 border-2 border-[#1B763B]/20 bg-[#1B763B]/5 rounded-2xl px-6 py-3">
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 items-center">
                <label class="col-span-12 md:col-span-3 text-lg font-bold text-[#1B763B]">Password Baru</label>
                <div class="col-span-12 md:col-span-9">
                    <input type="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah password"
                        class="w-full border-2 border-[#1B763B]/20 bg-[#1B763B]/5 rounded-2xl px-6 py-3 outline-none mb-2">
                    <p class="text-xs text-gray-400 italic">*Isi hanya jika ingin mereset password santri ini.</p>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 items-center">
                <label class="col-span-12 md:col-span-3 text-lg font-bold text-[#473829]">Kelas</label>
                <div class="col-span-12 md:col-span-9 relative">
                    <select name="kelas" required class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-3 outline-none appearance-none">
                        <option value="">-- Pilih kelas --</option>
                        {{-- Logika untuk memilih opsi yang sesuai dengan data database --}}
                        <option value="10-A" {{ old('kelas', $santri->kelas) == '10-A' ? 'selected' : '' }}>10-A</option>
                        <option value="11-B" {{ old('kelas', $santri->kelas) == '11-B' ? 'selected' : '' }}>11-B</option>
                        <option value="12-C" {{ old('kelas', $santri->kelas) == '12-C' ? 'selected' : '' }}>12-C</option>
                    </select>
                    <div class="absolute right-6 top-4 pointer-events-none text-gray-400">▼</div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 items-center">
                <label class="col-span-12 md:col-span-3 text-lg font-bold text-[#473829]">RFid</label>
                <input type="text" name="rfid_id" value="{{ old('rfid_id', $santri->rfid_id) }}" placeholder="Input Id Kartu" required
                    class="col-span-12 md:col-span-9 border-2 border-gray-100 bg-gray-50 rounded-2xl px-6 py-3 italic outline-none">
            </div>

            <div class="flex justify-end space-x-4 pt-10">
                <a href="{{ route('santri.index') }}" 
                   class="px-10 py-3 text-gray-400 font-bold hover:text-[#473829] transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-12 py-3 bg-[#1B763B] text-white rounded-2xl font-bold shadow-lg shadow-green-900/20 hover:bg-[#473829] transition transform hover:-translate-y-1">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection