<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-DISIPLIN | Tunas Qur'an</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-gray-50 antialiased font-sans">
    <div class="flex min-h-screen">
        <aside class="w-72 bg-[#473829] text-white flex flex-col fixed h-full shadow-2xl">
            <div class="p-8 text-center border-b border-white/5">
                <h1 class="font-berkshire text-3xl text-[#8BC53F]">Tunas Qur'an</h1>
            </div>

            <nav class="flex-1 px-6 space-y-3 mt-8">
                <p class="text-[10px] font-bold opacity-30 uppercase tracking-widest mb-4">Menu Utama</p>
                
                <a href="{{ route('kesiswaan.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-2xl transition {{ Request::is('*dashboard') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5' }}">
                    <i class="ph ph-squares-four mr-3 text-xl"></i> Dashboard
                </a>

                <a href="{{ route('santri.index') }}" 
                   class="flex items-center px-4 py-3 rounded-2xl transition {{ Request::is('*santri*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5' }}">
                    <i class="ph ph-users mr-3 text-xl"></i> Data Santriwati
                </a>
                <a href="{{ route('kegiatan.index') }}" 
   class="flex items-center px-4 py-3 rounded-2xl transition {{ Request::is('*kegiatan*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5' }}">
    <i class="ph ph-calendar mr-3 text-xl"></i> Manajemen Kegiatan
</a>
<a href="{{ route('user.index') }}" 
   class="flex items-center px-4 py-3 rounded-2xl transition {{ Request::is('*user*') ? 'bg-[#1B763B] shadow-lg font-bold' : 'text-white/60 hover:bg-white/5' }}">
    <i class="ph ph-user-gear mr-3 text-xl"></i> Manajemen Pengguna
</a>
            </nav>

            <div class="p-8 border-t border-white/5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="flex items-center text-red-400 font-bold hover:text-red-300 transition text-xs">
                        <i class="ph ph-sign-out mr-2"></i> KELUAR SISTEM
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 ml-72 flex flex-col">
            <header class="h-20 bg-white flex items-center justify-between px-12 shadow-sm">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Halaman</p>
                    <p class="font-bold text-[#1B763B] text-sm">Manajemen Data</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-bold text-[#473829]">{{ auth()->user()->username }}</p>
                        <p class="text-[10px] font-bold text-[#8BC53F] uppercase">Kesiswaan</p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-[#1B763B] flex items-center justify-center text-white shadow-lg">
                        <i class="ph ph-user text-2xl"></i>
                    </div>
                </div>
            </header>

            <main class="p-12">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>