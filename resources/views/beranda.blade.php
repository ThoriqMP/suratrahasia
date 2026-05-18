@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative text-center py-10 sm:py-20">
    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-pink-500/30 text-pink-300 text-xs font-bold tracking-widest uppercase mb-8">
        <span class="flex h-2 w-2 rounded-full bg-pink-500 animate-pulse"></span>
        Surat Cinta Digital Generasi Baru
    </div>
    
    <h1 class="text-5xl md:text-7xl font-black tracking-tight text-white mb-6">
        Abadikan Perasaanmu<br>
        <span class="text-gradient">Dalam Kata-Kata.</span>
    </h1>
    
    <p class="mt-6 text-lg md:text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed">
        Buat dan kirim surat cinta digital yang penuh makna secara rahasia. Karena setiap kata memiliki perasaan yang layak diabadikan selamanya.
    </p>
    
    <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
        <a href="/create" class="btn-immersive px-8 py-4 rounded-2xl font-bold text-white shadow-xl flex items-center justify-center gap-2 text-lg">
            <span>✍️</span> Tulis Surat Sekarang
        </a>
        <a href="/anonim" class="px-8 py-4 rounded-2xl font-bold text-white bg-white/10 hover:bg-white/20 border border-white/10 transition-all flex items-center justify-center gap-2 text-lg backdrop-blur-sm">
            <span>🎭</span> Pesan Anonim
        </a>
    </div>
</div>

<!-- Tutorial Section -->
<div class="mt-20 pt-16 border-t border-white/10">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-black text-white mb-4">Cara Menggunakan BucininAja</h2>
        <p class="text-slate-400">Tiga langkah mudah untuk mengirimkan pesan rahasia ke dia.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
        <!-- Step 1 -->
        <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-300 hover:shadow-[0_0_30px_rgba(244,114,182,0.2)] border-white/5 hover:border-pink-500/30 relative overflow-hidden">
            <div class="w-14 h-14 bg-pink-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform border border-pink-500/20">
                <span class="text-2xl">📝</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">1. Tulis Surat</h3>
            <p class="text-slate-400 leading-relaxed text-sm">
                Mulailah dengan mengklik tombol "Tulis Surat" di halaman utama. Tuangkan semua perasaanmu dengan jujur.
            </p>
        </div>

        <!-- Step 2 -->
        <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-300 hover:shadow-[0_0_30px_rgba(167,139,250,0.2)] border-white/5 hover:border-purple-500/30 relative overflow-hidden">
            <div class="w-14 h-14 bg-purple-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform border border-purple-500/20">
                <span class="text-2xl">🔒</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">2. Amankan Pesan</h3>
            <p class="text-slate-400 leading-relaxed text-sm">
                Pasang password khusus agar hanya dia yang bisa membuka. Atur juga waktu kedaluwarsa otomatis suratmu.
            </p>
        </div>

        <!-- Step 3 -->
        <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-300 hover:shadow-[0_0_30px_rgba(236,72,153,0.2)] border-white/5 hover:border-pink-500/30 relative overflow-hidden">
            <div class="w-14 h-14 bg-rose-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform border border-rose-500/20">
                <span class="text-2xl">💌</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">3. Bagikan Tautan</h3>
            <p class="text-slate-400 leading-relaxed text-sm">
                Setelah selesai, bagikan tautan unik rahasia itu kepadanya melalui WhatsApp atau DM Instagram.
            </p>
        </div>
    </div>
</div>

<!-- Pricing Section -->
<div class="mt-20 pt-16 border-t border-white/10 pb-10">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-black text-white mb-4">Paket Kredit Premium</h2>
        <p class="text-slate-400 max-w-xl mx-auto">Satu kredit berarti satu surat dengan pilihan desain tak terbatas. Dapatkan 1 kredit gratis saat mendaftar!</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto">
        @foreach($packages as $paket)
        <div class="glass-card p-6 text-center border {{ $paket->is_popular ? 'border-pink-500 shadow-[0_0_30px_rgba(244,114,182,0.2)] relative overflow-hidden' : 'border-white/10 hover:border-pink-500/50 hover:shadow-[0_0_30px_rgba(244,114,182,0.2)]' }} hover:-translate-y-2 transition-all group">
            @if($paket->is_popular)
                <div class="absolute top-0 right-0 bg-gradient-to-r from-pink-500 to-purple-500 text-[10px] font-bold text-white px-8 py-1 rotate-45 translate-x-[25px] translate-y-[10px] shadow-md">POPULER</div>
            @endif
            
            <span class="text-4xl block mb-4 group-hover:scale-110 transition-transform">
                @if($paket->jumlah_kredit <= 1) 💌
                @elseif($paket->jumlah_kredit <= 5) 💐
                @elseif($paket->is_popular) 🎁
                @else 👑
                @endif
            </span>
            <h3 class="text-xl font-bold text-white mb-1">{{ $paket->nama_paket }}</h3>
            <p class="text-pink-400 font-black text-2xl mb-4">Rp {{ number_format($paket->harga, 0, ',', '.') }}</p>
            <a href="/login" class="block w-full py-2 rounded-xl {{ $paket->is_popular ? 'btn-immersive shadow-lg' : 'bg-white/10 hover:bg-white/20' }} text-white font-bold transition-all">Beli Sekarang</a>
        </div>
        @endforeach
    </div>
</div>
@endsection
