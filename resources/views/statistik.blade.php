@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4 animate-fade-in-up">
    <div class="text-center mb-12">
        <div class="inline-block p-4 rounded-full bg-pink-500/10 border border-pink-500/20 mb-6 shadow-[0_0_30px_rgba(244,114,182,0.2)]">
            <span class="text-4xl filter drop-shadow-lg">📊</span>
        </div>
        <h2 class="text-3xl font-black text-white tracking-tight mb-2">Statistik BucininAja</h2>
        <p class="text-slate-400">Ringkasan pengiriman surat rahasia</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-12">
        <div class="glass-card p-8 text-center group hover:-translate-y-2 transition-all duration-300 hover:shadow-[0_0_30px_rgba(167,139,250,0.2)] hover:border-purple-500/30">
            <p class="text-sm text-slate-400 font-bold uppercase tracking-widest mb-2">Surat Dibuat</p>
            <p class="text-5xl font-black text-white text-gradient">{{ $jumlahSurat }}</p>
        </div>

        <div class="glass-card p-8 text-center group hover:-translate-y-2 transition-all duration-300 hover:shadow-[0_0_30px_rgba(244,114,182,0.2)] hover:border-pink-500/30">
            <p class="text-sm text-slate-400 font-bold uppercase tracking-widest mb-2">Surat Dibuka</p>
            <p class="text-5xl font-black text-white text-gradient">{{ $jumlahDibuka }}</p>
        </div>
    </div>

    <!-- Form Pencarian Surat -->
    <div class="glass-card p-8 md:p-10 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-pink-500/5 to-purple-500/5 pointer-events-none"></div>
        <h3 class="text-xl font-bold text-white mb-6 text-center">Cari Status Surat (Kode)</h3>
        
        <form action="{{ route('statistik.cari') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-4 relative z-10">
            @csrf
            <input
                type="text"
                name="kode"
                placeholder="Masukkan kode unik..."
                class="flex-1 w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none font-mono"
                required
            >
            <button
                type="submit"
                class="w-full sm:w-auto px-8 py-4 btn-immersive text-white rounded-2xl font-black shadow-xl"
            >
                Cari
            </button>
        </form>

        @if(session('error'))
            <p class="mt-6 text-center text-rose-400 font-bold text-sm bg-rose-500/10 p-3 rounded-xl border border-rose-500/20">{{ session('error') }}</p>
        @endif
    </div>
</div>

<style>
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
