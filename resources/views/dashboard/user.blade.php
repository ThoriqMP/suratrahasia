@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 animate-fade-in-up">
    <!-- Header -->
    <div class="glass-card p-8 mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-pink-500/20 border-2 border-pink-500/30 flex items-center justify-center text-3xl">
                👤
            </div>
            <div>
                <h2 class="text-2xl font-black text-white">{{ $user->name }}</h2>
                <p class="text-slate-400">{{ $user->email }}</p>
            </div>
        </div>
        
        <div class="text-center md:text-right">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Sisa Kredit</p>
            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-400">
                {{ $user->credits }} 💎
            </div>
            <a href="{{ route('topup.form') }}" class="mt-3 inline-block bg-white/10 hover:bg-white/20 text-white text-sm font-bold py-2 px-4 rounded-xl border border-white/20 transition-all">
                + Top Up Kredit
            </a>
        </div>
    </div>

    <!-- Letter History -->
    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span>💌</span> Riwayat Surat Kamu</h3>
    
    @if($surats->isEmpty())
        <div class="glass-card p-10 text-center">
            <span class="text-4xl mb-4 block opacity-50">📭</span>
            <p class="text-slate-400 font-medium">Kamu belum membuat surat apapun.</p>
            <a href="/create" class="mt-4 inline-block btn-immersive text-white font-bold py-2 px-6 rounded-xl">Buat Surat Pertama</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($surats as $surat)
                <div class="glass-card p-6 relative group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-pink-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Untuk</p>
                                <p class="text-lg font-bold text-white">{{ $surat->untuk }}</p>
                            </div>
                            <span class="px-2 py-1 bg-white/10 rounded text-xs text-slate-300 font-mono">{{ $surat->kode }}</span>
                        </div>
                        <p class="text-sm text-slate-400 line-clamp-2 mb-4">{{ $surat->isi }}</p>
                        
                        <div class="flex items-center justify-between border-t border-white/10 pt-4">
                            <span class="text-xs text-slate-500">{{ $surat->created_at->diffForHumans() }}</span>
                            <div class="flex gap-2">
                                <a href="/surat/{{ $surat->kode }}" target="_blank" class="text-xs font-bold text-pink-400 hover:text-pink-300">Lihat Surat ↗</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
