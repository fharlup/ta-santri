<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SI-DISIPLIN | Tunas Qur'an</title>

    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-berkshire { font-family: 'Berkshire Swash', cursive; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

    <aside class="w-72 bg-[#473829] text-white flex flex-col fixed h-full shadow-2xl z-50 overflow-hidden">
        <div class="p-8 text-center border-b border-white/5">
            <div class="flex flex-col items-center space-y-3">
                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center p-3 shadow-inner text-[#1B763B]">
                    <i class="ph ph-mosque text-4xl"></i>
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

            @if(auth()->user()->role !== 'Santri')
                <p class="text-[10px] font-bold opacity-30 uppercase tracking-[0.2em] px-4 mt-6 mb-2">Presensi</p>
                <a href="{{ route('presensi.scan') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*scan*') ? 'bg-[#1B763B] font-bold' : 'text-white/60 hover:bg-white/5' }}">
                    <i class="ph ph-barcode mr-3 text-xl"></i> Scan RFID
                </a>
                <a href="{{ route('presensi.riwayat') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*riwayat*') ? 'bg-[#1B763B] font-bold' : 'text-white/60 hover:bg-white/5' }}">
                    <i class="ph ph-clock-counter-clockwise mr-3 text-xl"></i> Riwayat Presensi
                </a>
                <a href="{{ route('presensi.rekap') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*rekap-presensi*') ? 'bg-[#1B763B] font-bold' : 'text-white/60 hover:bg-white/5' }}">
                    <i class="ph ph-chart-pie mr-3 text-xl"></i> Rekap Presensi
                </a>

                <p class="text-[10px] font-bold opacity-30 uppercase tracking-[0.2em] px-4 mt-6 mb-2">Penilaian</p>
                <a href="{{ route('penilaian.create') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*penilaian/create*') ? 'bg-[#1B763B] font-bold' : 'text-white/60 hover:bg-white/5' }}">
                    <i class="ph ph-pencil-line mr-3 text-xl"></i> Input Penilaian
                </a>
                <a href="{{ route('penilaian.rekap') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*rekap-penilaian*') ? 'bg-[#1B763B] font-bold' : 'text-white/60 hover:bg-white/5' }}">
                    <i class="ph ph-medal mr-3 text-xl"></i> Rekap Penilaian
                </a>
            @endif

            {{-- Manajemen untuk Kesiswaan dan Komdis --}}
            @if(in_array(auth()->user()->role, ['Kesiswaan', 'Komdis']))
                <p class="text-[10px] font-bold opacity-30 uppercase tracking-[0.2em] px-4 mt-6 mb-2">Manajemen</p>
                <a href="{{ route('kegiatan.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*kegiatan*') ? 'bg-[#1B763B] font-bold' : 'text-white/60 hover:bg-white/5' }}">
                    <i class="ph ph-calendar-check mr-3 text-xl"></i> Manajemen Kegiatan
                </a>
                <a href="{{ route('santri.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*santri*') ? 'bg-[#1B763B] font-bold' : 'text-white/60 hover:bg-white/5' }}">
                    <i class="ph ph-users-four mr-3 text-xl"></i> Manajemen Santriwati
                </a>
            @endif

            {{-- Khusus Kesiswaan --}}
            @if(auth()->user()->role == 'Kesiswaan')
                <a href="{{ route('user.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*user*') ? 'bg-[#1B763B] font-bold' : 'text-white/60 hover:bg-white/5' }}">
                    <i class="ph ph-user-gear mr-3 text-xl"></i> Manajemen Pengguna
                </a>
                <a href="{{ route('master.index') }}" 
                   class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*master-data*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <i class="ph ph-list-numbers mr-3 text-xl"></i> Master Angkatan & Kelas
                </a>

                <a href="{{ route('presensi.export') }}" class="flex items-center px-4 py-3 rounded-2xl text-[#8BC53F] border border-[#8BC53F]/20 mt-4 hover:bg-[#8BC53F] hover:text-white transition">
                    <i class="ph ph-file-xls mr-3 text-xl"></i> <span class="text-xs font-bold uppercase">Export Laporan</span>
                </a>
            @endif
        </nav>

        <div class="p-8 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-red-500/10 text-red-400 font-bold rounded-2xl hover:bg-red-500 hover:text-white transition-all">
                    <i class="ph ph-power text-xl"></i> <span class="text-xs uppercase">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-72 min-h-screen">
        <header class="h-20 bg-white border-b flex items-center justify-between px-12 sticky top-0 z-40">
            <h2 class="text-sm font-bold text-[#473829]">SI-DISIPLIN Tunas Qur'an</h2>
            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <p class="text-sm font-bold text-[#473829]">{{ Auth::user()->nama_lengkap }}</p>
                    <p class="text-[10px] font-black text-[#1B763B] uppercase">{{ Auth::user()->role }}</p>
                </div>
            </div>
        </header>
        <div class="p-12">
            @yield('content')
        </div>
    </main>
</body>
</html>