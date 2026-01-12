@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto pb-10">
    <div class="flex items-center space-x-4 mb-8">
        <div class="w-2 h-10 bg-[#1B763B] rounded-full"></div>
        <h1 class="font-berkshire text-4xl text-[#473829]">Form Penilaian Santri</h1>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#473829] p-10">
        <form action="{{ route('penilaian.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 bg-gray-50 p-8 rounded-[30px] border border-gray-100">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-[#1B763B] uppercase tracking-widest">Pilih Santriwati</label>
                    <select name="santriwati_id" id="santri-select" class="w-full bg-white border-2 border-gray-100 rounded-2xl px-5 py-3 outline-none focus:border-[#473829] font-bold text-[#473829]" required>
                        <option value="">-- Pilih Nama --</option>
                        @foreach($santris as $s)
                            <option value="{{ $s->id }}" data-nim="{{ $s->nim }}" data-angkatan="{{ $s->angkatan }}">{{ $s->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">NIM / ID</label>
                    <input type="text" id="display-nim" class="w-full bg-gray-100 border-none rounded-2xl px-5 py-3 font-bold text-gray-500" readonly placeholder="-">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-[#1B763B] uppercase tracking-widest">Tanggal Penilaian</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full bg-white border-2 border-gray-100 rounded-2xl px-5 py-3 font-bold text-[#473829]" required>
                    <input type="hidden" name="angkatan" id="display-angkatan">
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
                            $karakter = [
                                'disiplin' => 'Disiplin',
                                'k3' => 'K3 (Kebersihan, Keindahan, Ketertiban)',
                                'tanggung_jawab' => 'Tanggung Jawab',
                                'inisiatif_kreatifitas' => 'Inisiatif dan Kreatifitas',
                                'adab' => 'Adab',
                                'berterate' => 'Berterate'
                            ];
                        @endphp
                        @foreach($karakter as $key => $label)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-5 font-bold text-[#473829]">{{ $label }}</td>
                            <td class="px-6 py-5">
                                <select name="{{ $key }}" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 font-bold text-sm" required>
                                    <option value="B">B (Terbina)</option>
                                    <option value="A">A (Sangat Terbina)</option>
                                    <option value="C">C (Perlu Dibina)</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach

                        <tr class="bg-gray-50">
                            <td colspan="2" class="px-6 py-4 font-black text-[#1B763B] uppercase text-[10px]">7. Integritas Santri:</td>
                        </tr>
                        @php
                            $integritas = [
                                'integritas_kesabaran' => 'Kesabaran Dalam Berproses',
                                'integritas_produktif' => 'Produktif',
                                'integritas_mandiri' => 'Mandiri',
                                'integritas_optimis' => 'Optimis',
                                'integritas_kejujuran' => 'Kejujuran'
                            ];
                        @endphp
                        @foreach($integritas as $key => $label)
                        <tr>
                            <td class="px-6 py-5 pl-10 font-medium text-[#473829]">• {{ $label }}</td>
                            <td class="px-6 py-5">
                                <select name="{{ $key }}" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 font-bold text-sm" required>
                                    <option value="B">B (Terbina)</option>
                                    <option value="A">A (Sangat Terbina)</option>
                                    <option value="C">C (Perlu Dibina)</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mb-10">
                <label class="text-xs font-black text-[#473829] uppercase mb-4 block">Deskripsi / Catatan Musyrifah</label>
                <textarea name="deskripsi" rows="4" class="w-full bg-gray-50 border-2 border-gray-100 rounded-[30px] p-6 outline-none focus:border-[#473829] font-medium" placeholder="Tuliskan detail pengamatan..."></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="submit" class="px-16 py-4 bg-[#473829] text-white font-black rounded-2xl shadow-xl hover:bg-[#1B763B] transition-all uppercase text-xs tracking-widest">Submit Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif