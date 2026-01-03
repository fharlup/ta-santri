@extends('layouts.app')

@section('content')
<div class="space-y-12">
    <h1 class="font-berkshire text-5xl text-brand-brown">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="bg-[#E2E2E2] rounded-[40px] p-10 text-center shadow-sm">
            <h3 class="text-sm font-bold text-brand-brown mb-2">Presensi Hari Ini</h3>
            <p class="text-9xl font-bold text-brand-brown leading-none">{{ $hadirHariIni }}</p>
        </div>

        <div class="bg-[#E2E2E2] rounded-[40px] p-10 text-center shadow-sm">
            <h3 class="text-sm font-bold text-brand-brown mb-2">Terlambat</h3>
            <p class="text-9xl font-bold text-brand-brown leading-none">{{ $terlambatHariIni }}</p>
        </div>

        <div class="bg-[#E2E2E2] rounded-[40px] p-10 text-center shadow-sm">
            <h3 class="text-sm font-bold text-brand-brown mb-2">Tidak Hadir</h3>
            <p class="text-9xl font-bold text-brand-brown leading-none">{{ $tidakHadir }}</p>
        </div>

        <div class="bg-[#E2E2E2] rounded-[40px] p-10 text-center shadow-sm">
            <h3 class="text-sm font-bold text-brand-brown mb-2">Total Santriwati</h3>
            <p class="text-9xl font-bold text-brand-brown leading-none">{{ $totalSantri }}</p>
        </div>
    </div>

    <div class="bg-[#E2E2E2] rounded-[30px] p-8 shadow-sm">
        <h3 class="text-lg font-bold text-brand-brown mb-6">Grafik Kehadiran Per Jam</h3>
        <canvas id="chartRealtime" class="w-full h-80"></canvas>
    </div>
</div>

@endsection