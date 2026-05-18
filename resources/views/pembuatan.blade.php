@extends('layouts.app')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center px-4">

    <!-- Glowing Heart Icon -->
    <div class="relative mb-10">
        <div class="absolute inset-0 bg-pink-500 blur-[30px] rounded-full opacity-50 animate-pulse"></div>
        <div class="relative bg-white/10 border border-white/20 p-6 rounded-full backdrop-blur-md animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-pink-400" fill="currentColor" viewBox="0 0 24 24" stroke="none">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3
                  7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3
                  16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55
                  11.54L12 21.35z"/>
            </svg>
        </div>
    </div>

    <h1 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">Halaman Sedang <span class="text-gradient">Dibangun</span></h1>
    
    <p class="text-slate-400 text-lg md:text-xl max-w-2xl leading-relaxed mb-10">
        Kami sedang merangkai sesuatu yang sangat spesial, layaknya cinta yang tumbuh perlahan.
        Bersabarlah sebentar, karena keindahan ini akan segera hadir untukmu.
    </p>
    
    <a href="/" class="btn-immersive text-white font-bold px-8 py-4 rounded-2xl flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Beranda
    </a>
</div>
@endsection
