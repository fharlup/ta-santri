<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-DISIPLIN | Tunas Qur'an</title>

    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Montserrat:wght@300;400;700;900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .font-berkshire { font-family: 'Berkshire Swash', cursive; }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #1B763B; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 text-[#473829]">

    <div class="flex min-h-screen">
        {{-- SIDEBAR --}}
        <aside class="w-72 bg-[#473829] text-white flex flex-col fixed h-full z-50">
            {{-- Logo --}}
            <div class="p-8">
                <h1 class="font-berkshire text-2xl text-white">SI-DISIPLIN</h1>
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-white/40">Tunas Qur'an Boarding</p>
            </div>

            {{-- User Info Singkat --}}
            <div class="px-8 pb-8">
                <div class="flex items-center space-x-3 bg-white/5 p-4 rounded-2xl">
                    <div class="w-10 h-10 bg-[#1B763B] rounded-xl flex items-center justify-center font-bold text-white shadow-lg">
                        {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-black truncate uppercase">{{ auth()->user()->nama_lengkap }}</p>
                        <p class="text-[9px] font-bold text-white/40 uppercase tracking-wider">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                <p class="px-4 text-[9px] font-black text-white/20 uppercase tracking-widest mb-2">Main Menu</p>
                
                {{-- Dashboard: Bisa diakses SEMUA role --}}
                <a href="{{ route('kesiswaan.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('dashboard') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <i class="ph ph-house mr-3 text-xl"></i> Dashboard
                </a>

                {{-- Menu untuk Staff (Bukan Santri) --}}
                @if(auth()->user()->role !== 'Santri')
                    <a href="{{ route('presensi.scan') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*scan*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                        <i class="ph ph-qr-code mr-3 text-xl"></i> Scan Presensi
                    </a>

                    <a href="{{ route('penilaian.create') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*penilaian/create*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                        <i class="ph ph-note-pencil mr-3 text-xl"></i> Input Penilaian
                    </a>
                @endif

                {{-- Menu Khusus KESISWAAN (ADMIN) --}}
                @if(auth()->user()->role == 'Kesiswaan')
                    <div class="pt-6 pb-2 px-4 text-[9px] font-black text-white/20 uppercase tracking-widest">Administrator</div>
                    
                    <a href="{{ route('santri.index') }}" 
                       class="flex items-center px-4 py-3 rounded-2xl transition-all {{ Request::is('*santri*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                        <i class="ph ph-student mr-3 text-xl"></i> Manajemen Santri
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
                       class="flex items-center px-4 py-3 rounded-2xl transition-all text-[#8BC53F] hover:bg-[#8BC53F] hover:text-white mt-4 border border-[#8BC53F]/20">
                        <i class="ph ph-file-xls mr-3 text-xl"></i> <span class="text-xs font-black uppercase">Export Laporan</span>
                    </a>
                @endif
            </nav>

            {{-- Logout --}}
            <div class="p-8 border-t border-white/5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-2xl transition-all font-bold text-xs uppercase tracking-widest">
                        <i class="ph ph-sign-out mr-3 text-xl"></i> Keluar Sistem
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <main class="flex-1 ml-72">
            {{-- Top Header --}}
            <header class="bg-white/80 backdrop-blur-md h-20 flex items-center justify-between px-10 sticky top-0 z-40 border-b border-gray-100">
                <div class="flex items-center space-x-2">
                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Sistem Informasi Kedisiplinan v2.0</span>
                </div>
                <div class="text-right">
                    <p id="clock" class="text-sm font-black text-[#473829]"></p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ date('d F Y') }}</p>
                </div>
            </header>

            {{-- Content Wrapper --}}
            <div class="p-10">
                {{-- Notifikasi Sukses --}}
                @if(session('success'))
                    <div class="mb-8 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm flex items-center justify-between">
                        <span class="font-bold text-sm uppercase tracking-tighter">{{ session('success') }}</span>
                        <i class="ph ph-check-circle text-2xl"></i>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    {{-- Jam Realtime Script --}}
    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('clock').textContent = timeString + ' WIB';
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

</body>
</html>