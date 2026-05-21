@extends('layouts.app')

@section('content')
<div x-data="{ sent: {{ session('success') ? 'true' : 'false' }} }" class="max-w-md mx-auto py-6 animate-fade-in-up select-none">
    
    <!-- STATE 1: SUCCESS SCREEN (FLYING HEARTS / CONFETTI MOCKUP) -->
    <div x-show="sent" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="glass-card p-8 text-center space-y-6 border-pink-500/20 shadow-[0_0_50px_rgba(236,72,153,0.15)] relative overflow-hidden" x-cloak>
        <div class="absolute inset-0 bg-gradient-to-b from-pink-500/5 to-transparent pointer-events-none"></div>
        
        <!-- Animated Hearts -->
        <div class="relative w-24 h-24 mx-auto flex items-center justify-center">
            <span class="text-6xl animate-bounce-subtle">💖</span>
            <span class="absolute top-0 left-2 text-xl animate-ping opacity-75">✨</span>
            <span class="absolute bottom-2 right-2 text-xl animate-pulse">🌸</span>
        </div>
        
        <div class="space-y-2">
            <h2 class="text-2xl font-black text-white">Bisikan Terkirim!</h2>
            <p class="text-slate-300 text-sm leading-relaxed">
                Pesan rahasiamu berhasil ditiupkan ke dalam room pemilik. Identitasmu tetap aman terlindungi secara <strong class="text-pink-400">100% anonim</strong>.
            </p>
        </div>

        <div class="p-4 bg-white/5 rounded-2xl border border-white/5 text-xs text-slate-400 font-semibold leading-relaxed">
            🤔 Ingin menerima pesan anonim dari teman-temanmu juga? Buat link kamu sendiri sekarang gratis!
        </div>

        <a href="{{ route('anon.create') }}" class="block w-full py-4 bg-gradient-to-r from-orange-500 via-pink-500 to-rose-500 hover:from-orange-600 hover:via-pink-600 hover:to-rose-600 text-white font-extrabold rounded-2xl text-sm uppercase tracking-wider transition-all transform hover:-translate-y-0.5 shadow-lg shadow-pink-500/20">
            👉 Buat Link Saya Sendiri
        </a>
    </div>

    <!-- STATE 2: INPUT FORM SCREEN -->
    <div x-show="!sent" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <!-- Main NGL Card -->
        <div class="rounded-[32px] bg-gradient-to-b from-[#ff3f6c] to-[#ff6b3f] p-6 shadow-2xl relative overflow-hidden flex flex-col items-center text-center">
            <!-- Decorative Sparks -->
            <div class="absolute top-8 left-4 text-white/30 text-lg animate-pulse pointer-events-none">✨</div>
            <div class="absolute bottom-20 right-6 text-white/20 text-xl animate-pulse pointer-events-none">✨</div>
            
            <!-- Custom Header / User Profile Initial Mock -->
            <div class="space-y-2 z-10 w-full flex flex-col items-center">
                <div class="w-14 h-14 rounded-full bg-white/20 border-2 border-white/40 flex items-center justify-center text-2xl shadow-xl shadow-black/10">
                    🤫
                </div>
                <div class="space-y-0.5">
                    <p class="text-white font-black text-sm uppercase tracking-widest">Bisik Rahasia</p>
                    <p class="text-white/80 text-xs font-semibold">Kirim aku pesan anonim!</p>
                </div>
            </div>

            <!-- Card Question Box -->
            <div class="bg-white rounded-2xl p-5 shadow-xl w-full z-10 mt-6 border border-pink-100/50 text-left relative">
                <form action="{{ route('anon.message.store', $room->kode_form) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 flex items-center gap-1">
                            <span>✍️</span> Isi Pesan Rahasiamu
                        </label>
                        <textarea name="isi" rows="4" maxlength="1000" placeholder="Tulis bisikan rahasiamu di sini... (Jangan malu-malu, ini 100% anonim!)" 
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#ff3f6c] focus:ring-2 focus:ring-[#ff3f6c]/10 text-slate-800 text-sm placeholder-slate-400 outline-none transition-all duration-300 resize-none" required>{{ old('isi') }}</textarea>
                    </div>

                    @error('isi')
                        <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                    @enderror

                    <!-- Submit Pulsing Button -->
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-slate-900 to-slate-800 hover:from-black hover:to-slate-900 text-white font-black rounded-xl text-xs uppercase tracking-widest transition-all transform hover:-translate-y-0.5 shadow-lg shadow-black/20 flex items-center justify-center gap-2">
                        <span>🚀</span> Kirim Secara Anonim
                    </button>
                </form>
            </div>

            <!-- Shield Safety Badge -->
            <div class="z-10 bg-slate-950/20 backdrop-blur-md rounded-full py-1.5 px-4 border border-white/10 mt-6 flex items-center gap-2">
                <span class="text-xs">🔒</span>
                <span class="text-white font-bold text-[9px] tracking-widest uppercase">100% Aman & Rahasia</span>
            </div>
        </div>
        
        <!-- Back To Home link -->
        <div class="text-center mt-6">
            <a href="/" class="text-xs text-slate-500 hover:text-slate-400 transition-colors uppercase tracking-widest font-black">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>

</div>

<style>
    /* Premium Styling Overrides */
    body {
        background-color: #050811 !important;
    }
    
    .glass-card {
        background: rgba(15, 23, 42, 0.45) !important;
        border: 1px solid rgba(244, 114, 182, 0.15) !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important;
        backdrop-filter: blur(20px) !important;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-bounce-subtle {
        animation: bounceSubtle 2s infinite ease-in-out;
    }
    @keyframes bounceSubtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
</style>
@endsection
