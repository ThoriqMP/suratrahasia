@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <div class="text-center mb-12">
        <div class="inline-block p-4 rounded-full bg-pink-500/10 border border-pink-500/20 mb-6 shadow-[0_0_30px_rgba(244,114,182,0.2)]">
            <span class="text-4xl filter drop-shadow-lg">🕊️</span>
        </div>
        <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight">Tentang Website Ini</h2>
    </div>

    <div class="glass-card p-8 md:p-12 relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-pink-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative z-10 space-y-6 text-slate-300 text-lg leading-relaxed">
            
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-xs font-bold uppercase tracking-widest text-pink-400 mb-2">
                <span>📅</span> 23 Mei 2025
            </div>

            <p>
                Website ini dibuat dengan tujuan sederhana: <strong class="text-white font-bold">membantu orang-orang menyampaikan perasaan mereka lewat surat cinta digital</strong>.
            </p>

            <p>
                Di era yang serba cepat dan digital, kadang kita kesulitan mengekspresikan isi hati. Maka dari itu, website ini hadir sebagai ruang aman dan romantis untuk menuangkan kata-kata yang mungkin sulit diucapkan secara langsung.
            </p>

            <p>
                Semoga setiap surat yang ditulis di sini bisa menyampaikan cinta, harapan, dan keberanian untuk mencintai — meski lewat layar.
            </p>
        </div>
    </div>

    <div class="mt-12 text-center">
        <a href="/" class="inline-flex items-center gap-2 text-sm font-bold text-pink-400 hover:text-pink-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
