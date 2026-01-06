@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto pb-10">
    <div class="flex items-center space-x-4 mb-8">
        <div class="w-2 h-10 bg-[#1B763B] rounded-full"></div>
        <h1 class="font-berkshire text-4xl text-[#473829]">Edit Penilaian Santri</h1>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#473829] p-10">
        <form action="{{ route('penilaian.update', $penilaian->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 bg-gray-50 p-8 rounded-[30px] border border-gray-100">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-[#1B763B] uppercase tracking-widest">Santriwati</label>
                    <input type="text" value="{{ $penilaian->santriwati->nama_lengkap }}" class="w-full bg-gray-100 border-none rounded-2xl px-5 py-3 font-bold text-gray-500" readonly>
                    <input type="hidden" name="santriwati_id" value="{{ $penilaian->santriwati_id }}">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">NIM</label>
                    <input type="text" value="{{ $penilaian->santriwati->nim }}" class="w-full bg-gray-100 border-none rounded-2xl px-5 py-3 font-bold text-gray-500" readonly>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-[#1B763B] uppercase tracking-widest">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $penilaian->tanggal->format('Y-m-d') }}" class="w-full bg-white border-2 border-gray-100 rounded-2xl px-5 py-3 font-bold text-[#473829]" required>
                    <input type="hidden" name="angkatan" value="{{ $penilaian->angkatan }}">
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-100 mb-10">
                <table class="w-full text-left">
                    <thead class="bg-[#473829] text-white">
                        <tr>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-widest">Muatan Karakter</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-widest w-64">Nilai (Predikat)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $muatan = [
                                'disiplin' => 'Disiplin', 'k3' => 'K3', 'tanggung_jawab' => 'Tanggung Jawab',
                                'inisiatif_kreatifitas' => 'Inisiatif dan Kreatifitas', 'adab' => 'Adab', 'berterate' => 'Berterate'
                            ];
                            $integritas = [
                                'integritas_kesabaran' => 'Kesabaran', 'integritas_produktif' => 'Produktif', 
                                'integritas_mandiri' => 'Mandiri', 'integritas_optimis' => 'Optimis', 'integritas_kejujuran' => 'Kejujuran'
                            ];
                        @endphp

                        @foreach($muatan as $key => $label)
                        <tr>
                            <td class="px-6 py-5 font-bold text-[#473829]">{{ $label }}</td>
                            <td class="px-6 py-5">
                                <select name="{{ $key }}" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 font-bold text-sm">
                                    <option value="A" {{ $penilaian->$key == 'A' ? 'selected' : '' }}>A (Sangat Terbina)</option>
                                    <option value="B" {{ $penilaian->$key == 'B' ? 'selected' : '' }}>B (Terbina)</option>
                                    <option value="C" {{ $penilaian->$key == 'C' ? 'selected' : '' }}>C (Perlu Dibina)</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach

                        <tr class="bg-gray-50"><td colspan="2" class="px-6 py-3 font-black text-[#1B763B] text-[10px] uppercase">7. Integritas Santri:</td></tr>
                        
                        @foreach($integritas as $key => $label)
                        <tr>
                            <td class="px-6 py-5 pl-10 text-[#473829] font-medium">• {{ $label }}</td>
                            <td class="px-6 py-5">
                                <select name="{{ $key }}" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 font-bold text-sm">
                                    <option value="A" {{ $penilaian->$key == 'A' ? 'selected' : '' }}>A (Sangat Terbina)</option>
                                    <option value="B" {{ $penilaian->$key == 'B' ? 'selected' : '' }}>B (Terbina)</option>
                                    <option value="C" {{ $penilaian->$key == 'C' ? 'selected' : '' }}>C (Perlu Dibina)</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mb-10">
                <label class="text-xs font-black text-[#473829] uppercase mb-4 block">Catatan Tambahan</label>
                <textarea name="deskripsi" rows="4" class="w-full bg-gray-50 border-2 border-gray-100 rounded-[30px] p-6 font-medium text-[#473829]">{{ $penilaian->deskripsi }}</textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('penilaian.rekap') }}" class="px-10 py-4 text-gray-400 font-bold uppercase text-xs tracking-widest">Batal</a>
                <button type="submit" class="px-16 py-4 bg-[#473829] text-white font-black rounded-2xl shadow-xl hover:bg-[#1B763B] transition-all uppercase text-xs tracking-widest">Update Data</button>
            </div>
        </form>
    </div>
</div>
@endsection