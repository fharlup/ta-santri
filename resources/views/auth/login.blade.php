@extends('layouts.auth')

@section('content')
<div class="flex min-h-screen flex-col md:flex-row">
    
    <div class="flex w-full flex-col justify-center bg-[#569174] p-8 md:w-5/12 lg:p-16">
        <div class="mx-auto w-full max-w-md">
            <div class="mb-10 text-white">
                <h1 class="text-4xl font-bold leading-tight">Login</h1>
                <p class="mt-2 text-lg font-medium opacity-90">Selamat Datang</p>
                <p class="text-sm opacity-80">Silahkan Memasukkan Username dan Password Anda</p>
            </div>

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="mb-2 block text-sm font-semibold text-white">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}"
                        class="w-full rounded-full border-none bg-white px-6 py-3 text-brand-brown outline-none ring-offset-2 focus:ring-2 focus:ring-brand-light-green"
                        placeholder="Username">
                    @error('username')
                        <span class="mt-1 block text-xs text-red-200">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-semibold text-white">Password</label>
                    <input type="password" name="password"
                        class="w-full rounded-full border-none bg-white px-6 py-3 text-brand-brown outline-none ring-offset-2 focus:ring-2 focus:ring-brand-light-green"
                        placeholder="Password">
                </div>

                <div class="mb-8 flex items-center justify-between text-white">
                    <label class="flex items-center space-x-2 text-sm cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-none text-brand-dark-green focus:ring-0">
                        <span>Ingat Saya?</span>
                    </label>
                    <a href="#" class="text-sm font-semibold hover:underline">Lupa Password</a>
                </div>

                <button type="submit" 
                    class="w-full rounded-full bg-white py-3 text-sm font-bold tracking-widest text-[#569174] shadow-lg transition duration-200 hover:bg-gray-100 active:scale-95 uppercase">
                    Login
                </button>
            </form>
        </div>
    </div>

    <div class="hidden w-full items-center justify-center bg-white md:flex md:w-7/12">
        <div class="text-center">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Tunas Qur'an" class="mx-auto w-80 h-auto object-contain">
            
            
        </div>
    </div>

</div>
@endsection