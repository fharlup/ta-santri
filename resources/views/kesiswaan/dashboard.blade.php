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

    // ===== CHART HARI INI =====
    const dataDay = @json($chartData);
    new Chart(document.getElementById('chartHariIni'), {
        type: 'bar',
        data: {
            labels: Object.keys(dataDay).map(h => h + ':00'),
            datasets: [{
                data: Object.values(dataDay),
                backgroundColor: brandColor,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // ===== CHART MINGGUAN =====
    const dataWeek = @json($weeklyData);
    new Chart(document.getElementById('chartMingguIni'), {
        type: 'bar',
        data: {
            labels: Object.keys(dataWeek).map(d =>
                new Date(d).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit'
                })
            ),
            datasets: [{
                data: Object.values(dataWeek),
                backgroundColor: brandColor,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
</script>
@endsection
