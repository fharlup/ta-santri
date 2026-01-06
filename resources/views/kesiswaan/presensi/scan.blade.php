@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto pb-10">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-2 h-10 bg-[#1B763B] rounded-full"></div>
            <h1 class="font-berkshire text-4xl text-[#473829]">Terminal Presensi RFID</h1>
        </div>
        <div class="text-right">
            <p class="text-xs font-black text-[#1B763B] uppercase tracking-widest">Toleransi Keterlambatan</p>
            <p class="text-lg font-bold text-[#473829]">10 Menit </p>
        </div>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl border-t-[12px] border-[#473829] overflow-hidden">
        <div class="grid grid-cols-12">
            
            <div id="status-container" class="col-span-12 md:col-span-5 bg-[#473829]/5 p-12 flex flex-col items-center justify-center border-r border-gray-100 transition-all duration-500">
                <div id="status-box" class="w-72 h-96 bg-white rounded-[40px] shadow-xl flex flex-col items-center justify-center border-2 border-dashed border-gray-200 transition-all duration-500">
                    <i id="status-icon" class="ph ph-rfid text-[120px] text-gray-200"></i>
                    <h2 id="status-text-big" class="font-black text-4xl mt-6 hidden tracking-tighter text-white"></h2>
                </div>
                <div class="mt-8 text-center">
                    <p id="instruction" class="text-[#473829] font-bold opacity-40 uppercase tracking-[0.3em]">Siap Menerima Scan...</p>
                </div>
            </div>

            <div class="col-span-12 md:col-span-7 p-12 space-y-8">
                
                <div class="bg-[#473829] rounded-3xl p-6 shadow-inner border-4 border-[#1B763B]/20">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-[10px] font-black text-[#1B763B] uppercase tracking-widest">RFID Reader Live Stream</label>
                        <div id="scanner-led" class="flex items-center space-x-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Standby</span>
                            <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse shadow-[0_0_8px_red]"></div>
                        </div>
                    </div>
                    <p id="display-rfid-raw" class="text-4xl font-mono font-bold text-[#8BC53F] tracking-[0.2em] truncate">
                        <span class="opacity-10 text-2xl uppercase tracking-normal">Waiting for card...</span>
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="border-b-2 border-gray-100 pb-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Identitas Santriwati </label>
                        <p id="display-nama" class="text-3xl font-black text-[#473829]">____________________</p>
                        <div class="flex space-x-6 mt-1">
                            <p class="text-sm font-bold text-[#1B763B]">NIM: <span id="display-nim" class="text-[#473829]">----------</span></p>
                            <p class="text-sm font-bold text-[#1B763B]">ANGKATAN: <span id="display-angkatan" class="text-[#473829]">----------</span></p>
                        </div>
                    </div>

                    <div class="bg-[#1B763B]/5 p-6 rounded-[30px] border border-[#1B763B]/10">
                        <div class="flex justify-between items-center mb-4">
                            <span class="px-4 py-1 bg-[#1B763B] text-white text-[10px] font-black rounded-full uppercase">Kegiatan Aktif </span>
                            <span id="display-waktu" class="text-sm font-black text-[#473829] opacity-40 italic">--:--:--</span>
                        </div>
                        <p id="display-kegiatan" class="text-2xl font-black text-[#473829]">Mencari Jadwal Kegiatan...</p>
                        <p id="display-ustadzah" class="text-xs font-bold text-gray-400 mt-2 italic">Ustadzah Pendamping: - </p>
                    </div>
                </div>

                <div id="keterangan-wrapper" class="hidden transform transition-all duration-300">
                    <div class="bg-orange-50 border-2 border-orange-200 p-6 rounded-[35px] shadow-inner">
                        <label class="flex items-center text-orange-700 font-black text-xs uppercase mb-3">
                            <i class="ph ph-warning-circle mr-2 text-xl"></i> Alasan Terlambat (Wajib diisi) [cite: 18]
                        </label>
                        <input type="text" id="keterangan-input" 
                               class="w-full bg-white border-2 border-orange-300 rounded-2xl px-6 py-4 outline-none focus:border-orange-500 font-bold text-[#473829]"
                               placeholder="Ketik alasan (misal: Sakit) lalu tekan ENTER...">
                    </div>
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

    // Selalu paksa fokus untuk integrasi RFID Reader 
    document.addEventListener('click', () => {
        if (ketWrapper.classList.contains('hidden')) rfidInput.focus();
    });

    // Mirroring RFID Input 
    rfidInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            displayRfid.innerText = this.value;
            displayRfid.classList.remove('opacity-10');
            scannerLed.innerHTML = '<span class="text-[10px] font-bold text-yellow-600 uppercase">Receiving</span><div class="w-3 h-3 bg-yellow-500 rounded-full shadow-[0_0_8px_yellow]"></div>';
        }
    });

    // Submit via Enter (dari RFID Reader) [cite: 16, 17]
    rfidInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            currentRfid = this.value;
            this.value = '';
            scannerLed.innerHTML = '<span class="text-[10px] font-bold text-blue-600 uppercase">Processing</span><div class="w-3 h-3 bg-blue-500 rounded-full shadow-[0_0_8px_blue]"></div>';
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
                alert(data.message);
                resetToStandby();
            }
        });
    }

    function showLateWarning(data) {
        document.getElementById('display-nama').innerText = data.nama;
        document.getElementById('display-nim').innerText = data.nim;
        document.getElementById('display-angkatan').innerText = data.angkatan;
        
        const statusBox = document.getElementById('status-box');
        statusBox.className = "w-72 h-96 bg-orange-500 rounded-[40px] flex flex-col items-center justify-center text-white border-none shadow-2xl animate-pulse";
        document.getElementById('status-icon').className = "ph ph-clock-countdown text-[120px]";
        document.getElementById('status-text-big').innerText = "TELAT"; // [cite: 12, 18]
        document.getElementById('status-text-big').classList.remove('hidden');
        
        ketWrapper.classList.remove('hidden');
        ketInput.focus();
    }

    function showSuccess(data) {
        document.getElementById('display-nama').innerText = data.nama;
        document.getElementById('display-nim').innerText = data.nim;
        document.getElementById('display-angkatan').innerText = data.angkatan;
        document.getElementById('display-kegiatan').innerText = data.kegiatan; // 
        document.getElementById('display-ustadzah').innerText = "Ustadzah Pendamping: " + data.ustadzah; // 
        document.getElementById('display-waktu').innerText = data.waktu;
        
        const statusBox = document.getElementById('status-box');
        const statusText = document.getElementById('status-text-big');

        if (data.status === 'HADIR') { // [cite: 13]
            statusBox.className = "w-72 h-96 bg-[#1B763B] rounded-[40px] flex flex-col items-center justify-center text-white border-none shadow-2xl scale-105 transition-transform";
            document.getElementById('status-icon').className = "ph ph-check-circle text-[120px]";
            scannerLed.innerHTML = '<span class="text-[10px] font-bold text-green-600 uppercase">Success</span><div class="w-3 h-3 bg-green-500 rounded-full shadow-[0_0_8px_green]"></div>';
        } else {
            statusBox.className = "w-72 h-96 bg-orange-600 rounded-[40px] flex flex-col items-center justify-center text-white border-none shadow-2xl";
            document.getElementById('status-icon').className = "ph ph-warning text-[120px]";
            ketWrapper.classList.add('hidden');
        }
        
        statusText.innerText = data.status;
        statusText.classList.remove('hidden');

        setTimeout(() => { resetToStandby(); }, 3000); // Reset otomatis 
    }

    function resetToStandby() {
        document.getElementById('display-nama').innerText = "____________________";
        document.getElementById('display-nim').innerText = "----------";
        document.getElementById('display-angkatan').innerText = "----------";
        displayRfid.innerHTML = '<span class="opacity-10 text-2xl uppercase tracking-normal">Waiting for card...</span>';
        
        const statusBox = document.getElementById('status-box');
        statusBox.className = "w-72 h-96 bg-white rounded-[40px] shadow-xl flex flex-col items-center justify-center border-2 border-dashed border-gray-200";
        document.getElementById('status-icon').className = "ph ph-rfid text-[120px] text-gray-200";
        document.getElementById('status-text-big').classList.add('hidden');
        scannerLed.innerHTML = '<span class="text-[10px] font-bold text-gray-400 uppercase">Standby</span><div class="w-3 h-3 bg-red-500 rounded-full animate-pulse shadow-[0_0_8px_red]"></div>';
        
        rfidInput.focus();
    }

    ketInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') submitPresensi(currentRfid, this.value);
    });
</script>
@endsection