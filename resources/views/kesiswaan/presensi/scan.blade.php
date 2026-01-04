@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto pb-10">
    <div class="flex items-center space-x-4 mb-8">
        <div class="w-2 h-10 bg-[#1B763B] rounded-full"></div>
        <h1 class="font-berkshire text-4xl text-[#473829]">Terminal Presensi RFID</h1>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#473829] overflow-hidden">
        <div class="grid grid-cols-12">
            
            <div id="status-container" class="col-span-12 md:col-span-5 bg-[#473829]/5 p-12 flex flex-col items-center justify-center border-r border-gray-100">
                <div id="status-box" class="w-64 h-80 bg-white rounded-[30px] shadow-xl flex flex-col items-center justify-center border-2 border-dashed border-gray-200 transition-all duration-300">
                    <i id="status-icon" class="ph ph-rfid text-9xl text-gray-200"></i>
                    <h2 id="status-text-big" class="font-black text-2xl mt-4 hidden uppercase tracking-widest text-white"></h2>
                </div>
                <div class="mt-8 text-center">
                    <p id="instruction" class="text-[#473829] font-bold opacity-50 uppercase tracking-widest">Siap Menerima Scan...</p>
                </div>
            </div>

            <div class="col-span-12 md:col-span-7 p-12 space-y-8">
                
                <div class="bg-[#473829] rounded-2xl p-6 shadow-inner border-4 border-[#1B763B]/20">
                    <label class="text-[10px] font-black text-[#1B763B] uppercase tracking-widest block mb-2">Live RFID Scanner Input</label>
                    <div class="flex items-center justify-between">
                        <p id="display-rfid-raw" class="text-3xl font-mono font-bold text-[#8BC53F] tracking-[0.2em]">
                            <span class="opacity-20">WAITING...</span>
                        </p>
                        <div id="scanner-led" class="w-4 h-4 bg-red-500 rounded-full animate-pulse"></div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="group">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Santriwati</label>
                        <p id="display-nama" class="text-2xl font-bold text-[#473829] border-b-2 border-gray-100 pb-2">____________________</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">NIM / ID</label>
                            <p id="display-nim" class="text-lg font-bold text-[#473829]">__________</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kelas</label>
                            <p id="display-kelas" class="text-lg font-bold text-[#473829]">__________</p>
                        </div>
                    </div>
                </div>

                <div id="keterangan-wrapper" class="hidden">
                    <div class="bg-orange-50 border-2 border-orange-200 p-6 rounded-[30px] space-y-3">
                        <label class="text-orange-700 font-black text-sm uppercase flex items-center">
                            <i class="ph ph-warning-circle mr-2 text-xl"></i> Alasan Terlambat (Wajib diisi & Enter)
                        </label>
                        <input type="text" id="keterangan-input" 
                               class="w-full bg-white border-2 border-orange-300 rounded-2xl px-6 py-4 outline-none focus:border-orange-500 font-bold text-[#473829]"
                               placeholder="Contoh: Telat bangun tidur...">
                    </div>
                </div>

                <div class="pt-4 opacity-20 text-center">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em]">Sistem Berjalan Otomatis - Tidak Perlu Klik Tombol</p>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="text" id="rfid-hidden-input" class="absolute opacity-0 pointer-events-none" autofocus>

<script>
    const rfidInput = document.getElementById('rfid-hidden-input');
    const displayRfid = document.getElementById('display-rfid-raw');
    const scannerLed = document.getElementById('scanner-led');
    const ketInput = document.getElementById('keterangan-input');
    const ketWrapper = document.getElementById('keterangan-wrapper');
    let currentRfid = '';

    // Selalu paksa fokus agar scanner terbaca
    document.addEventListener('click', () => {
        if (ketWrapper.classList.contains('hidden')) rfidInput.focus();
    });

    // 1. Deteksi Alat RFID Mengetik (Mirroring)
    rfidInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            displayRfid.innerText = this.value;
            displayRfid.classList.remove('opacity-20');
            scannerLed.className = "w-4 h-4 bg-yellow-500 rounded-full shadow-[0_0_10px_yellow]";
        }
    });

    // 2. Kirim Data saat Enter (Otomatis dari alat)
    rfidInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            currentRfid = this.value;
            this.value = '';
            submitPresensi(currentRfid);
        }
    });

    function submitPresensi(rfid, keterangan = '') {
        fetch("{{ route('presensi.check') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ rfid_id: rfid, keterangan: keterangan })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.require_keterangan) {
                    showLateWarning(data);
                } else {
                    showSuccess(data);
                }
            } else {
                showError(data.message);
            }
        });
    }

    function showLateWarning(data) {
        // Perbaikan Undefined: Isi data santri dulu
        document.getElementById('display-nama').innerText = data.nama;
        document.getElementById('display-nim').innerText = data.nim;
        document.getElementById('display-kelas').innerText = data.kelas;
        
        document.getElementById('status-box').className = "w-64 h-80 bg-orange-500 rounded-[30px] flex flex-col items-center justify-center text-white border-none shadow-xl animate-pulse";
        document.getElementById('status-icon').className = "ph ph-clock-countdown text-9xl";
        document.getElementById('status-text-big').innerText = "TERLAMBAT";
        document.getElementById('status-text-big').classList.remove('hidden');
        
        ketWrapper.classList.remove('hidden');
        ketInput.focus(); // Santri wajib ketik alasan
    }

    function showSuccess(data) {
        // Update tampilan data
        document.getElementById('display-nama').innerText = data.nama;
        document.getElementById('display-nim').innerText = data.nim;
        document.getElementById('display-kelas').innerText = data.kelas;
        displayRfid.innerText = currentRfid;
        
        const statusBox = document.getElementById('status-box');
        const statusText = document.getElementById('status-text-big');
        statusBox.className = "w-64 h-80 bg-[#1B763B] rounded-[30px] flex flex-col items-center justify-center text-white border-none shadow-2xl scale-105 transition-transform";
        document.getElementById('status-icon').className = "ph ph-check-circle text-9xl";
        statusText.innerText = data.status;
        statusText.classList.remove('hidden');
        
        scannerLed.className = "w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_green]";
        ketWrapper.classList.add('hidden');

        // OTOMATIS RESET SETELAH 3 DETIK (Gak perlu pencet OK/Selesai)
        setTimeout(() => {
            resetToStandby();
        }, 3000);
    }

    function resetToStandby() {
        document.getElementById('display-nama').innerText = "____________________";
        document.getElementById('display-nim').innerText = "__________";
        document.getElementById('display-kelas').innerText = "__________";
        displayRfid.innerHTML = '<span class="opacity-20">WAITING...</span>';
        
        const statusBox = document.getElementById('status-box');
        statusBox.className = "w-64 h-80 bg-white rounded-[30px] shadow-xl flex flex-col items-center justify-center border-2 border-dashed border-gray-200 transition-all";
        document.getElementById('status-icon').className = "ph ph-rfid text-9xl text-gray-200";
        document.getElementById('status-text-big').classList.add('hidden');
        scannerLed.className = "w-4 h-4 bg-red-500 rounded-full animate-pulse shadow-[0_0_10px_red]";
        
        rfidInput.focus();
    }

    function showError(msg) {
        alert(msg);
        resetToStandby();
    }

    ketInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') submitPresensi(currentRfid, this.value);
    });
</script>
@endsection