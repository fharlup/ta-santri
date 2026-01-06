@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
        <div class="flex items-center space-x-5">
            <div class="w-3 h-14 bg-[#1B763B] rounded-full shadow-[0_0_20px_rgba(27,118,59,0.3)]"></div>
            <div>
                <h1 class="font-berkshire text-4xl text-[#473829]">Monitoring Presensi</h1>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.4em]">Persentase Kehadiran Santriwati (100%)</p>
            </div>
        </div>
        <a href="{{ route('presensi.export') }}" class="flex items-center px-6 py-4 bg-[#8BC53F] text-white rounded-2xl font-black text-xs uppercase hover:bg-[#1B763B] transition shadow-lg">
            <i class="ph ph-file-xls mr-2 text-2xl"></i> Export Excel
        </a>
    </div>

    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#473829] overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-8 py-8 text-[10px] font-black text-gray-400 uppercase sticky left-0 bg-gray-50 z-10">Santriwati</th>
                        @foreach($listKegiatan ?? [] as $keg)
                            <th class="px-4 py-8 text-[10px] font-black text-[#1B763B] uppercase text-center min-w-[120px]">{{ $keg }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rekapData ?? [] as $data)
                    <tr>
                        <td class="px-8 py-6 sticky left-0 bg-white shadow-sm z-10">
                            <p class="font-black text-[#473829] uppercase text-sm leading-tight">{{ $data['nama'] }}</p>
                            <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">{{ $data['angkatan'] }}</span>
                        </td>
                        @foreach($listKegiatan ?? [] as $keg)
                        <td class="px-4 py-6 text-center">
                            @php $v = $data[$keg] ?? 0; @endphp
                            <span class="inline-block px-4 py-2 rounded-xl {{ $v >= 85 ? 'text-green-600 bg-green-50' : ($v >= 75 ? 'text-orange-600 bg-orange-50' : 'text-red-600 bg-red-50') }} font-black text-xs shadow-sm">
                                {{ $v }}%
                            </span>
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr><td colspan="15" class="px-8 py-10 text-center text-gray-400">Data presensi tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection