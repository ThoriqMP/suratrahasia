@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-12 px-4 animate-fade-in-up">
    <div class="text-center mb-10">
        <div class="inline-block p-4 rounded-full bg-purple-500/10 border border-purple-500/20 mb-6 shadow-[0_0_30px_rgba(168,85,247,0.2)]">
            <span class="text-4xl filter drop-shadow-lg">🔍</span>
        </div>
        <h2 class="text-3xl font-black text-white tracking-tight">Hasil Pencarian</h2>
    </div>

    @if ($surat)
        <div class="glass-card p-8 border-l-4 border-l-emerald-500 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10 space-y-4">
                <div class="flex items-center justify-between border-b border-white/5 pb-4 mb-4">
                    <span class="text-sm font-bold text-slate-500 uppercase tracking-widest">Kode Surat</span>
                    <span class="font-mono font-black text-white bg-white/10 px-3 py-1 rounded-lg">{{ $surat->kode }}</span>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Dari</p>
                        <p class="font-bold text-white text-lg">{{ $surat->dari }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Untuk</p>
                        <p class="font-bold text-white text-lg">{{ $surat->untuk }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-white/5">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Status Surat</p>
                    @if($surat->dibuka_pada)
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-emerald-400 font-bold text-sm">Sudah Dibuka ({{ \Carbon\Carbon::parse($surat->dibuka_pada)->format('d M Y H:i') }})</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500/10 border border-amber-500/20">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            <span class="text-amber-400 font-bold text-sm">Belum Dibuka</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="glass-card p-10 text-center border-rose-500/20">
            <p class="text-rose-400 font-bold mb-2">Pencarian Gagal</p>
            <p class="text-slate-400">Surat dengan kode <code class="bg-white/10 px-2 py-1 rounded-md text-white font-mono">{{ $kode }}</code> tidak ditemukan dalam sistem.</p>
        </div>
    @endif

    <div class="text-center mt-12">
        <a href="{{ route('statistik.form') }}" class="inline-flex items-center gap-2 text-sm font-bold text-pink-400 hover:text-pink-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Pencarian
        </a>
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
