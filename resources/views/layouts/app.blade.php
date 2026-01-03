<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-DISIPLIN Tunas Qur'an</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-white antialiased">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-[#473829] text-white flex flex-col fixed h-full shadow-2xl">
            <div class="p-8">
                <h1 class="font-berkshire text-2xl text-[#8BC53F]">Tunas Qur'an</h1>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <p class="text-[10px] font-bold opacity-40 uppercase px-4 mb-2">Menu Utama</p>
                
                <a href="{{ route('kesiswaan.dashboard') }}" 
                   class="flex items-center px-4 py-2 rounded-xl text-xs transition {{ Request::is('*dashboard') ? 'bg-[#1B763B] font-bold' : 'text-white/70 hover:bg-white/10' }}">
                    <i class="ph ph-squares-four mr-3 text-lg"></i> Dashboard
                </a>

                <a href="{{ route('santri.index') }}" 
                   class="flex items-center px-4 py-2 rounded-xl text-xs transition {{ Request::is('*santri*') ? 'bg-[#1B763B] font-bold' : 'text-white/70 hover:bg-white/10' }}">
                    <i class="ph ph-users mr-3 text-lg"></i> Data Santriwati
                </a>

                <a href="#" class="flex items-center px-4 py-2 text-white/70 hover:bg-white/10 rounded-xl text-xs transition">
                    <i class="ph ph-calendar mr-3 text-lg"></i> Jadwal Kegiatan
                </a>
            </nav>

            <div class="p-6 border-t border-white/5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="text-red-400 text-xs font-bold hover:text-red-300">← KELUAR SISTEM</button>
                </form>
            </div>
        </aside>

        <div class="flex-1 ml-64 flex flex-col">
            <header class="h-16 bg-white flex items-center justify-between px-10 border-b border-gray-100">
                <p class="font-bold text-[#1B763B] text-sm">SI-DISIPLIN <span class="text-gray-300">/</span> Manajemen</p>
                <div class="flex items-center space-x-3">
                    <span class="text-xs font-bold text-[#473829]">{{ auth()->user()->username }}</span>
                    <div class="w-8 h-8 rounded-full bg-[#8BC53F]"></div>
                </div>
            </header>
            <main class="p-10">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>