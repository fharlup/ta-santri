@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- Notifikasi --}}
    @if(session('success') || session('error') || session('info') || session('warning'))
        <div class="mb-6 animate-bounce">
            <div class="px-6 py-4 rounded-2xl shadow-lg border-l-8 
                {{ session('success') ? 'bg-green-500 border-green-700' : '' }}
                {{ session('error') ? 'bg-red-500 border-red-700' : '' }}
                {{ session('warning') ? 'bg-yellow-500 border-yellow-700' : '' }}
                {{ session('info') ? 'bg-blue-500 border-blue-700' : '' }} text-white font-bold">
                {{ session('success') ?? session('error') ?? session('warning') ?? session('info') }}
            </div>
        </div>
    @endif

    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#1B763B] overflow-hidden">
        <div class="p-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                
                {{-- SISI KIRI: AREA SCAN --}}
                <div class="text-center space-y-6 border-r-2 border-gray-50 pr-6">
                    <div class="relative inline-block">
                        <div class="absolute -inset-4 bg-[#8BC53F]/20 rounded-full animate-ping"></div>
                        <div class="relative w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center border-4 border-[#1B763B]">
                            <i class="ph ph-broadcast text-6xl text-[#1B763B]"></i>
                        </div>
                    </div>
                    
                    <div>
                        <h1 class="font-berkshire text-4xl text-[#473829]">Terminal Scan RFID</h1>
                        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.3em] mt-2">Silakan Tapping Kartu Anda</p>
                    </div>

                    {{-- Form Input RFID (Otomatis Fokus) --}}
                    <form action="{{ route('presensi.check') }}" method="POST">
                        @csrf
                        <input type="text" name="rfid" id="rfid_input" autofocus autocomplete="off"
                            class="w-full bg-gray-100 border-2 border-dashed border-gray-300 rounded-2xl px-6 py-4 text-center font-black text-2xl tracking-[0.5em] focus:border-[#1B763B] focus:bg-white outline-none transition-all"
                            placeholder="•••• •••• ••••">
                        {{-- Masukkan kegiatan_id secara hidden jika kegiatan aktif ditemukan --}}
                        @if($kegiatanAktif)
                            <input type="hidden" name="kegiatan_id" value="{{ $kegiatanAktif->id }}">
                        @endif
                    </form>

                    {{-- Info Kegiatan Aktif --}}
                    <div class="bg-[#473829] rounded-3xl p-6 text-white shadow-xl transform hover:scale-105 transition">
                        <p class="text-[10px] font-black opacity-50 uppercase tracking-widest">Kegiatan Saat Ini:</p>
                        @if($kegiatanAktif)
                            <h2 class="text-2xl font-black text-[#8BC53F] uppercase">{{ $kegiatanAktif->nama_kegiatan }}</h2>
                            <p class="text-xs font-bold mt-1">Pukul: {{ \Carbon\Carbon::parse($kegiatanAktif->jam)->format('H:i') }} WIB</p>
                        @else
                            <h2 class="text-xl font-black text-red-400">TIDAK ADA JADWAL AKTIF</h2>
                            <p class="text-[9px] opacity-70">Sistem tidak menemukan jadwal jam ini</p>
                        @endif
                    </div>
                </div>

                {{-- SISI KANAN: DATA SANTRI (PLACEHOLDER) --}}
                <div class="space-y-8 bg-gray-50/50 p-8 rounded-[40px] border-2 border-white shadow-inner">
                    <div class="flex flex-col items-center text-center">
                        {{-- Foto Santri --}}
                        <div class="w-40 h-40 bg-white rounded-[40px] shadow-xl flex items-center justify-center p-4 mb-6 border-4 border-white overflow-hidden">
                            @if(session('last_santri'))
                                <i class="ph ph-user-circle text-8xl text-[#1B763B]"></i>
                            @else
                                <i class="ph ph-user-focus text-8xl text-gray-200"></i>
                            @endif
                        </div>

                        {{-- Nama & Angkatan --}}
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Santriwati</p>
                            <h3 class="text-3xl font-black text-[#473829] leading-tight">
                                {{ session('last_santri')->nama_lengkap ?? 'MENUNGGU SCAN...' }}
                            </h3>
                            <div class="flex items-center justify-center space-x-3 mt-2">
                                <span class="px-4 py-1 bg-white rounded-full text-[10px] font-black text-[#1B763B] shadow-sm uppercase tracking-tighter">
                                    Angkatan: {{ session('last_santri')->angkatan ?? '-' }}
                                </span>
                                <span class="px-4 py-1 bg-[#1B763B] text-white rounded-full text-[10px] font-black shadow-sm uppercase tracking-tighter">
                                    Status: {{ session('status_absen') ?? 'OFFLINE' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Quote / Pesan Motivasi --}}
                    <div class="bg-white/80 p-6 rounded-3xl text-center border border-white">
                        <p class="text-xs italic text-gray-400">"Kedisiplinan adalah jembatan antara cita-cita dan pencapaian."</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Script agar input selalu fokus --}}
@push('scripts')
<script>
    const rfidInput = document.getElementById('rfid_input');
    
    // Autofocus kembali jika klik di mana saja
    document.addEventListener('click', () => {
        rfidInput.focus();
    });

    // Mencegah form disubmit jika input kosong
    rfidInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && this.value === '') {
            e.preventDefault();
        }
    });
</script>
@endpush
@endsection