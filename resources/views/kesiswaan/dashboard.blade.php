@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-10 px-4 sm:px-6 lg:px-8">

    {{-- ==========================================
         1. TAMPILAN KHUSUS SANTRI (SIMPEL)
         ========================================== --}}
    @if(auth()->user()->role == 'Santri')
        <div class="mb-10">
            <h1 class="font-berkshire text-4xl text-[#473829]">Assalamu'alaikum, {{ auth()->user()->nama_lengkap }}</h1>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Pantau kehadiran dan nilai ahlakmu di sini</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            {{-- Card Kehadiran --}}
            <div class="bg-white p-8 rounded-[40px] shadow-sm border-t-[10px] border-green-500">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-4">Total Hadir</p>
                <h2 class="text-5xl font-black text-[#473829]">{{ $data['total_hadir'] ?? 0 }}</h2>
            </div>

            {{-- Nilai Adab --}}
            <div class="bg-white p-8 rounded-[40px] shadow-sm border-t-[10px] border-blue-500">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-4">Nilai Adab Terakhir</p>
                <h2 class="text-5xl font-black text-blue-500">{{ $data['nilai_terakhir']->adab ?? '-' }}</h2>
            </div>

            {{-- Nilai Disiplin --}}
            <div class="bg-white p-8 rounded-[40px] shadow-sm border-t-[10px] border-amber-500">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-4">Nilai Disiplin Terakhir</p>
                <h2 class="text-5xl font-black text-amber-500">{{ $data['nilai_terakhir']->disiplin ?? '-' }}</h2>
            </div>
        </div>

        {{-- Tabel Riwayat Singkat --}}
        <div class="bg-white rounded-[40px] shadow-xl border-t-[15px] border-[#473829] overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                <h3 class="font-black text-[#473829] uppercase text-sm tracking-widest">5 Presensi Terakhir</h3>
            </div>
            <table class="w-full text-left">
                <tbody class="divide-y divide-gray-50">
                    @forelse($data['presensi'] as $p)
                    <tr>
                        <td class="px-8 py-5">
                            <p class="font-black text-[#473829] uppercase text-xs">{{ $p->kegiatan->nama_kegiatan }}</p>
                            <p class="text-[9px] text-gray-400 font-bold uppercase">{{ \Carbon\Carbon::parse($p->waktu_scan)->format('d M Y - H:i') }}</p>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="px-4 py-1.5 rounded-full font-black text-[9px] uppercase {{ $p->status == 'Hadir' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                {{ $p->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td class="py-10 text-center text-gray-300 font-bold uppercase text-[10px]">Belum ada riwayat</td></tr>
                    @endforelse
                </tbody>
            </table>
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