@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10 px-4 sm:px-6 lg:px-8">

    {{-- ==========================================
         1. TAMPILAN KHUSUS SANTRI (SIMPEL)
         ========================================== --}}
    @if(auth()->user()->role == 'Santri')
       <div class="mb-10">
            <h1 class="font-berkshire text-4xl text-[#473829]">Assalamu'alaikum, {{ auth()->user()->nama_lengkap ?? auth()->user()->name }}</h1>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Pantau kehadiran dan nilai ahlakmu di sini</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-10">
            {{-- Card Ringkasan Kehadiran --}}
            <div class="lg:col-span-1">
                <div class="bg-white p-8 rounded-[40px] shadow-sm border-t-[10px] border-[#1B763B] h-full flex flex-col justify-center">
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-4 text-center">Total Kehadiran</p>
                    <h2 class="text-7xl font-black text-[#473829] text-center">{{ $data['total_hadir'] ?? 0 }}</h2>
                    <p class="text-[9px] text-gray-400 font-bold uppercase mt-4 text-center italic">Alhamdulillah, tingkatkan terus!</p>
                </div>
            </div>

            {{-- Grid Penilaian Karakter (6 Aspek) --}}
            <div class="lg:col-span-3">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @php
                        $penilaian = $data['nilai_terakhir'] ?? null;
                        $aspek = [
                            'Disiplin' => $penilaian->disiplin ?? '-',
                            'K3' => $penilaian->k3 ?? '-',
                            'Adab' => $penilaian->adab ?? '-',
                            'Tj. Jawab' => $penilaian->tanggung_jawab ?? '-',
                            'Kejujuran' => $penilaian->integritas_kejujuran ?? '-',
                            'Mandiri' => $penilaian->integritas_mandiri ?? '-'
                        ];
                    @endphp

                    @foreach($aspek as $nama => $nilai)
                    <div class="bg-white p-6 rounded-[30px] shadow-sm border border-gray-100 flex flex-col items-center text-center">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">{{ $nama }}</span>
                        
                        @php
                            $color = match($nilai) {
                                'A' => 'text-green-600 bg-green-50',
                                'B' => 'text-blue-600 bg-blue-50',
                                'C' => 'text-amber-600 bg-amber-50',
                                'D' => 'text-red-600 bg-red-50',
                                default => 'text-gray-300 bg-gray-50'
                            };
                        @endphp
                        
                        <div class="w-14 h-14 rounded-2xl {{ $color }} flex items-center justify-center text-2xl font-black shadow-inner">
                            {{ $nilai }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pesan Asatidz & Riwayat --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Catatan Khusus --}}
            <div class="bg-[#1B763B]/5 border border-[#1B763B]/10 p-8 rounded-[40px]">
                <h4 class="text-[10px] font-black text-[#1B763B] uppercase tracking-widest mb-4 flex items-center">
                    <i class="ph ph-chat-centered-text mr-2 text-xl"></i> Pesan Untukmu
                </h4>
                @if($penilaian && $penilaian->deskripsi)
                    <p class="text-sm text-[#473829] italic font-medium leading-relaxed">"{{ $penilaian->deskripsi }}"</p>
                @else
                    <p class="text-sm text-gray-400 italic">Belum ada pesan khusus dari asatidz.</p>
                @endif
            </div>

            {{-- 5 Presensi Terakhir --}}
            <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="font-black text-[#473829] uppercase text-[10px] tracking-widest flex items-center">
                        <i class="ph ph-clock-counter-clockwise mr-2 text-lg"></i> 5 Presensi Terakhir
                    </h3>
                </div>
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-50">
                        @forelse($data['presensi'] as $p)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-black text-[#473829] uppercase text-[11px]">{{ $p->kegiatan->nama_kegiatan ?? 'Kegiatan' }}</p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase">{{ \Carbon\Carbon::parse($p->waktu_scan)->format('d M, H:i') }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-3 py-1 rounded-full font-black text-[8px] uppercase {{ $p->status == 'Hadir' || $p->status == 'HADIR' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td class="py-10 text-center text-gray-300 font-bold uppercase text-[9px]">Belum ada riwayat</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    {{-- ==========================================
         2. TAMPILAN KESISWAAN / USTADZAH
         ========================================== --}}
    @else
        <div class="space-y-8">
            <h1 class="font-berkshire text-4xl text-[#473829]">Dashboard Kesiswaan</h1>

            {{-- STAT CARD --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gray-200 rounded-[40px] p-6 text-center shadow-sm">
                    <h3 class="text-sm font-bold text-[#473829] mb-1">Presensi Hari Ini</h3>
                    <p class="text-8xl font-bold text-[#473829] leading-none">{{ $hadirHariIni }}</p>
                </div>

                <div class="bg-gray-200 rounded-[40px] p-6 text-center shadow-sm">
                    <h3 class="text-sm font-bold text-[#473829] mb-1">Terlambat</h3>
                    <p class="text-8xl font-bold text-[#473829] leading-none">{{ $terlambatHariIni }}</p>
                </div>

                <div class="bg-gray-200 rounded-[40px] p-6 text-center shadow-sm">
                    <h3 class="text-sm font-bold text-[#473829] mb-1">Tidak Hadir</h3>
                    <p class="text-8xl font-bold text-[#473829] leading-none">{{ $tidakHadir }}</p>
                </div>

                <div class="bg-gray-200 rounded-[40px] p-6 text-center shadow-sm">
                    <h3 class="text-sm font-bold text-[#473829] mb-1">Total Santriwati</h3>
                    <p class="text-8xl font-bold text-[#473829] leading-none">{{ $totalSantri }}</p>
                </div>
            </div>

            {{-- CHART SECTION --}}
            <div class="space-y-6">
                <div class="bg-gray-200 rounded-[30px] p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-[#473829] mb-4">Chart Presensi Hari Ini</h3>
                    <div class="relative h-48">
                        <canvas id="chartHariIni"></canvas>
                    </div>
                </div>

                <div class="bg-gray-200 rounded-[30px] p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-[#473829] mb-4">Chart Presensi Minggu Ini</h3>
                    <div class="relative h-48">
                        <canvas id="chartMingguIni"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Script Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const brandColor = '#473829';
            const accentColor = '#1B763B';

            // Chart Harian
            const dataDay = @json($chartData);
            new Chart(document.getElementById('chartHariIni'), {
                type: 'bar',
                data: {
                    labels: dataDay.labels,
                    datasets: [{
                        label: 'Jumlah Tap RFID',
                        data: dataDay.values,
                        backgroundColor: accentColor,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });

            // Chart Mingguan
            const dataWeek = @json($weeklyData);
            new Chart(document.getElementById('chartMingguIni'), {
                type: 'line',
                data: {
                    labels: dataWeek.labels,
                    datasets: [{
                        label: 'Total Kehadiran',
                        data: dataWeek.values,
                        borderColor: brandColor,
                        backgroundColor: 'rgba(71, 56, 41, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        </script>
    @endif

</div>
@endsection