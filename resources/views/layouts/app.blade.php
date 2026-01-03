<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-DISIPLIN | Tunas Qur'an</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 antialiased">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-[#E2E2E2] flex flex-col shadow-inner">
            <div class="p-8 text-center">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-24 mx-auto mb-4">
                <h1 class="font-berkshire text-xl text-brand-dark-green">Tunas Qur'an</h1>
            </div>
            
            <nav class="flex-1 px-4 space-y-2">
                <a href="#" class="flex items-center px-6 py-2 bg-[#569174] text-white rounded-full font-bold">Home</a>
                <a href="#" class="flex items-center px-6 py-2 text-brand-brown hover:bg-white/50 rounded-full transition">Presensi</a>
                <a href="#" class="flex items-center px-6 py-2 text-brand-brown hover:bg-white/50 rounded-full transition">Penilaian</a>
                <a href="#" class="flex items-center px-6 py-2 text-brand-brown hover:bg-white/50 rounded-full transition">Manajemen</a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="h-20 bg-white flex items-center justify-end px-12 space-x-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-[#569174]"></div>
                    <span class="font-bold text-brand-brown">{{ auth()->user()->username }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-[#569174] text-white px-6 py-1 rounded-lg text-sm font-bold">Logout</button>
                </form>
            </header>

            <main class="p-12 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>