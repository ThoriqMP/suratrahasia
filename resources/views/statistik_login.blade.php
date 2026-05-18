@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-12 px-4">
    <div class="text-center mb-10 animate-fade-in-down">
        <div class="inline-block p-4 rounded-full bg-purple-500/10 border border-purple-500/20 mb-6 shadow-[0_0_30px_rgba(168,85,247,0.2)]">
            <span class="text-4xl filter drop-shadow-lg">🔐</span>
        </div>
        <h2 class="text-3xl font-black text-white tracking-tight mb-2">Login Statistik</h2>
        <p class="text-slate-400 text-sm font-medium">Masukkan password administrator</p>
    </div>

    <div class="glass-card p-8 md:p-12 relative overflow-hidden animate-fade-in-up">
        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm font-bold text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('statistik.show') }}" method="POST" class="space-y-6">
            @csrf
            <div class="group">
                <input type="password" name="password" id="password"
                       class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 focus:bg-white/10 text-white placeholder-slate-500 text-center tracking-widest text-lg transition-all duration-300 outline-none"
                       placeholder="••••••••" required>
            </div>

            <button type="submit"
                    class="w-full btn-immersive text-white font-black py-4 rounded-2xl flex items-center justify-center gap-2 shadow-xl">
                <span>🔎</span> Akses Data
            </button>
        </form>
    </div>
</div>

<style>
    .animate-fade-in-down { animation: fadeInDown 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
