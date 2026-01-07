@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <div class="flex items-center space-x-4 mb-8">
        <div class="w-2 h-10 bg-[#1B763B] rounded-full"></div>
        <h1 class="font-berkshire text-4xl text-[#473829]">Terminal Scan RFID</h1>
    </div>

    <div class="bg-white rounded-[50px] shadow-2xl border-t-[15px] border-[#1B763B] p-12 text-center relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#1B763B]/5 rounded-full"></div>
        
        <div class="relative z-10">
            <div class="mb-8 inline-flex items-center justify-center w-24 h-24 bg-[#1B763B]/10 rounded-full text-[#1B763B] animate-pulse">
                <i class="ph-bold ph-broadcast text-5xl"></i>
            </div>

            <h2 class="text-2xl font-black text-[#473829] mb-2 uppercase tracking-tighter">Siap Memindai Kartu</h2>
            <p class="text-gray-400 text-sm mb-10 font-medium">Tempelkan kartu RFID santriwati pada alat scanner</p>

            @if(session('success'))
                <div class="mb-8 p-4 bg-green-50 border-2 border-green-100 rounded-3xl flex items-center justify-center space-x-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-green-500 text-2xl"></i>
                    <span class="font-black text-green-700 uppercase text-xs tracking-widest">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-4 bg-red-50 border-2 border-red-100 rounded-3xl flex items-center justify-center space-x-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-red-500 text-2xl"></i>
                    <span class="font-black text-red-700 uppercase text-xs tracking-widest">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('presensi.check') }}" method="POST" id="rfid-form">
                @csrf
                <input type="hidden" name="kegiatan_id" value="{{ $kegiatanAktif->id ?? '' }}">
                
                <div class="relative max-w-md mx-auto">
                    <input type="text" 
                           name="rfid" 
                           id="rfid_input" 
                           class="w-full bg-gray-50 border-4 border-dashed border-gray-200 rounded-[30px] px-6 py-12 text-center text-4xl font-black text-[#473829] tracking-[0.5em] focus:border-[#1B763B] focus:bg-white outline-none transition-all placeholder:text-gray-200"
                           placeholder="••••••••"
                           autofocus 
                           autocomplete="off">
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-center space-x-8">
                <div class="text-left">
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Kegiatan Saat Ini:</p>
                    <p class="font-bold text-[#1B763B] uppercase text-sm">{{ $kegiatanAktif->nama_kegiatan ?? 'Tidak Ada Kegiatan Aktif' }}</p>
                </div>
                <div class="w-px h-8 bg-gray-100"></div>
                <div class="text-left">
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Waktu:</p>
                    <p class="font-bold text-[#473829] uppercase text-sm">{{ now()->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white/50 border border-dashed border-gray-200 p-6 rounded-[30px] flex items-center space-x-4">
            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-[#473829] font-bold">1</div>
            <p class="text-xs font-bold text-gray-500 uppercase leading-relaxed">Kursor otomatis fokus pada area scan</p>
        </div>
        <div class="bg-white/50 border border-dashed border-gray-200 p-6 rounded-[30px] flex items-center space-x-4">
            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-[#473829] font-bold">2</div>
            <p class="text-xs font-bold text-gray-500 uppercase leading-relaxed">Tap kartu dan data terkirim otomatis</p>
        </div>
        <div class="bg-white/50 border border-dashed border-gray-200 p-6 rounded-[30px] flex items-center space-x-4">
            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-[#473829] font-bold">3</div>
            <p class="text-xs font-bold text-gray-500 uppercase leading-relaxed">Tanpa perlu input keterangan tambahan</p>
        </div>
    </div>
</div>

<script>
    const rfidInput = document.getElementById('rfid_input');

    // 1. Selalu Fokus ke Input (meski user klik di luar kotak)
    document.addEventListener('click', () => {
        rfidInput.focus();
    });

    // 2. Auto-submit saat scanner mendeteksi kartu (biasanya diakhiri dengan 'Enter' oleh scanner)
    // Script ini juga menangani jika scanner memasukkan karakter secara cepat
    let timer;
    rfidInput.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            if (rfidInput.value.length >= 5) { // Minimal digit RFID
                document.getElementById('rfid-form').submit();
            }
        }, 300); // Tunggu 300ms setelah input berhenti untuk submit
    });
</script>
@endsection