@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <h1 class="font-berkshire text-4xl text-[#473829]">Dashboard</h1>

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

    {{-- CHART FULL WIDTH (ATAS - BAWAH) --}}
    <div class="space-y-6">
        {{-- CHART HARI INI --}}
        <div class="bg-gray-200 rounded-[30px] p-6 shadow-sm">
            <h3 class="text-lg font-bold text-[#473829] mb-4">
                Chart Presensi Hari Ini
            </h3>
            {{-- FIX HEIGHT --}}
            <div class="relative h-48">
                <canvas id="chartHariIni"></canvas>
            </div>
        </div>

        {{-- CHART MINGGUAN --}}
        <div class="bg-gray-200 rounded-[30px] p-6 shadow-sm">
            <h3 class="text-lg font-bold text-[#473829] mb-4">
                Chart Presensi Minggu Ini
            </h3>
            {{-- FIX HEIGHT --}}
            <div class="relative h-48">
                <canvas id="chartMingguIni"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const brandColor = '#473829';
    const accentColor = '#1B763B';

    // ===== FIX GRAFIK HARIAN (Urutan Jam) =====
    const dataDay = @json($chartData);
    new Chart(document.getElementById('chartHariIni'), {
        type: 'bar',
        data: {
            labels: dataDay.labels, // Mengambil label jam yang sudah urut dari PHP
            datasets: [{
                label: 'Jumlah Tap RFID',
                data: dataDay.values, // Mengambil nilai count
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

    // ===== FIX GRAFIK MINGGUAN (Invalid Date) =====
    const dataWeek = @json($weeklyData);
    new Chart(document.getElementById('chartMingguIni'), {
        type: 'line',
        data: {
            labels: dataWeek.labels, // Menampilkan "07 Jan", "08 Jan", dst
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
@endsection
