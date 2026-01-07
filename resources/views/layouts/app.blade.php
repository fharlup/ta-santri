<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SI-DISIPLIN | Tunas Qur'an</title>

    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-berkshire { font-family: 'Berkshire Swash', cursive; }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

    <aside class="w-72 bg-[#473829] text-white flex flex-col fixed h-full shadow-2xl z-50 overflow-hidden">
        
        <div class="p-8 text-center border-b border-white/5">
            <div class="flex flex-col items-center space-y-3">
                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center p-3 shadow-inner">
                    <img src="{{ asset('img/logo-tunas-quran.png') }}" alt="Logo" class="max-h-full">
                </div>
                <h1 class="font-berkshire text-2xl text-[#8BC53F] tracking-wide">Tunas Qur'an</h1>
            </div>
        </div>

        <nav class="flex-1 px-6 space-y-2 mt-6 overflow-y-auto custom-scrollbar">
            
            <p class="text-[10px] font-bold opacity-30 uppercase tracking-[0.2em] px-4 mb-2">Utama</p>
            <a href="{{ route('kesiswaan.dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*dashboard') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                <i class="ph ph-house-line mr-3 text-xl"></i> Home
            </a>

            <p class="text-[10px] font-bold opacity-30 uppercase tracking-[0.2em] px-4 mt-6 mb-2">Presensi</p>
            <a href="{{ route('presensi.scan') }}" 
               class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*scan*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                <i class="ph ph-barcode mr-3 text-xl"></i> Scan RFID
            </a>
            <a href="{{ route('presensi.riwayat') }}" 
               class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*riwayat*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                <i class="ph ph-clock-counter-clockwise mr-3 text-xl"></i> Riwayat Presensi
            </a>
            <a href="{{ route('presensi.rekap') }}" 
               class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*rekap-presensi*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                <i class="ph ph-chart-pie mr-3 text-xl"></i> Rekap Presensi
            </a>

            <p class="text-[10px] font-bold opacity-30 uppercase tracking-[0.2em] px-4 mt-6 mb-2">Penilaian</p>
            <a href="{{ route('penilaian.create') }}" 
               class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*penilaian/create*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                <i class="ph ph-pencil-line mr-3 text-xl"></i> Input Penilaian
            </a>
            <a href="{{ route('penilaian.rekap') }}" 
               class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*rekap-penilaian*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                <i class="ph ph-medal mr-3 text-xl"></i> Rekap Penilaian
            </a>

            <p class="text-[10px] font-bold opacity-30 uppercase tracking-[0.2em] px-4 mt-6 mb-2">Manajemen</p>
            <a href="{{ route('kegiatan.index') }}" 
               class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*kegiatan*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                <i class="ph ph-calendar-check mr-3 text-xl"></i> Manajemen Kegiatan
            </a>
            <a href="{{ route('santri.index') }}" 
               class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*santri*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                <i class="ph ph-users-four mr-3 text-xl"></i> Manajemen Santriwati
            </a>
            <a href="{{ route('user.index') }}" 
   class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*user*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
    <i class="ph ph-user-gear mr-3 text-xl"></i> Manajemen Pengguna
</a>
<a href="{{ route('master.index') }}" 
   class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*master-data*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
    <i class="ph ph-list-numbers mr-3 text-xl"></i> Master Angkatan & Kelas
</a>
<a href="{{ route('presensi.export') }}" 
   class="flex items-center px-4 py-3 rounded-2xl transition-all text-white/60 hover:bg-[#8BC53F] hover:text-white shadow-sm">
    <i class="ph ph-file-xls mr-3 text-xl text-[#8BC53F] group-hover:text-white"></i> 
    <span class="text-sm font-bold">Export Rekap Presensi</span>
</a>
        </nav>

        <div class="p-8 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-red-500/10 text-red-400 font-bold rounded-2xl hover:bg-red-500 hover:text-white transition-all duration-300">
                    <i class="ph ph-power text-xl"></i>
                    <span class="text-xs uppercase tracking-widest">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-72 min-h-screen flex flex-col">
        
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-12 sticky top-0 z-40">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sistem Kedisiplinan</p>
                <h2 class="text-sm font-bold text-[#473829]">SI-DISIPLIN Tunas Qur'an</h2>
            </div>
            
            <div class="flex items-center space-x-6">
                <div class="text-right">
                    <p class="text-sm font-bold text-[#473829]">{{ Auth::user()->nama_lengkap ?? 'Pengguna' }}</p>
                    <p class="text-[10px] font-black text-[#1B763B] uppercase">{{ Auth::user()->role ?? 'Staff' }}</p>
                </div>
                <div class="w-10 h-10 bg-[#1B763B] rounded-full flex items-center justify-center text-white">
                    <i class="ph ph-user text-xl"></i>
                </div>
            </div>
        </header>

        <div class="p-12">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>