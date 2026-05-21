@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 animate-fade-in-up">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side: Creator Information -->
        <div class="space-y-6 text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-500/10 border border-pink-500/30 text-xs font-bold text-pink-400 uppercase tracking-wider animate-pulse">
                <span>💬</span> Fitur Terbaru
            </div>
            
            <h1 class="text-4xl md:text-5xl font-black text-white leading-tight tracking-tight">
                Bisik <span class="bg-gradient-to-r from-orange-400 via-pink-500 to-rose-500 bg-clip-text text-transparent">Rahasia</span>
            </h1>
            
            <p class="text-slate-300 text-base md:text-lg leading-relaxed">
                Dapatkan link rahasia buatanmu sendiri secara instan! Bagikan ke Instagram Stories, WhatsApp, atau sosial media lainnya dan biarkan teman-temanmu mengirimkan pesan secara <strong class="text-pink-400">100% anonim</strong>.
            </p>
            
            <div class="space-y-3">
                <div class="flex items-center gap-3 text-slate-300 text-sm">
                    <span class="w-6 h-6 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center font-bold text-xs">1</span>
                    <span>Buat room rahasiamu hanya dengan satu klik.</span>
                </div>
                <div class="flex items-center gap-3 text-slate-300 text-sm">
                    <span class="w-6 h-6 rounded-full bg-pink-500/20 text-pink-400 flex items-center justify-center font-bold text-xs">2</span>
                    <span>Bagikan link curahan hati ke bio atau story sosial mediamu.</span>
                </div>
                <div class="flex items-center gap-3 text-slate-300 text-sm">
                    <span class="w-6 h-6 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center font-bold text-xs">3</span>
                    <span>Buka amplop misterius berisi curhatan jujur mereka!</span>
                </div>
            </div>

            <!-- Form Submit -->
            <form action="{{ route('anon.store') }}" method="POST" class="pt-4">
                @csrf
                <button type="submit" class="w-full md:w-auto px-8 py-4 bg-gradient-to-r from-orange-500 via-pink-500 to-rose-500 hover:from-orange-600 hover:via-pink-600 hover:to-rose-600 text-white font-extrabold rounded-2xl shadow-[0_0_30px_rgba(236,72,153,0.3)] hover:shadow-[0_0_40px_rgba(236,72,153,0.5)] transform hover:-translate-y-0.5 transition-all text-center text-lg flex items-center justify-center gap-3 uppercase tracking-wider">
                    <span>💌</span> Buat Link Sekarang
                </button>
            </form>
            
            <p class="text-xs text-slate-500 italic">
                *Tanpa perlu daftar atau login. Privasi terjaga penuh.
            </p>
        </div>

        <!-- Right Side: Smartphone Mock-up Preview -->
        <div class="flex justify-center relative">
            <!-- Decorative Glow Backgrounds -->
            <div class="absolute -inset-4 bg-gradient-to-tr from-orange-500/20 to-rose-500/20 blur-3xl rounded-full pointer-events-none"></div>
            
            <!-- Phone Frame -->
            <div class="w-[280px] h-[520px] rounded-[40px] border-[10px] border-slate-900 bg-slate-950 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.8)] overflow-hidden relative flex flex-col items-center p-4">
                <!-- Phone Speaker / Notch -->
                <div class="absolute top-2 left-1/2 -translate-x-1/2 w-28 h-4 bg-slate-900 rounded-full z-20"></div>
                
                <!-- Phone Content (NGL style mockup card) -->
                <div class="w-full h-full bg-gradient-to-b from-[#ff3f6c] to-[#ff6b3f] rounded-[24px] p-4 flex flex-col justify-between relative overflow-hidden text-center select-none pt-8">
                    <!-- Particle sparkles -->
                    <div class="absolute top-12 left-6 text-white/30 animate-pulse">✨</div>
                    <div class="absolute bottom-24 right-8 text-white/30 animate-pulse">✨</div>
                    <div class="absolute top-1/2 right-4 text-white/20">❤️</div>
                    <div class="absolute bottom-1/3 left-6 text-white/20">❤️</div>
                    
                    <!-- App Logo/Header -->
                    <div class="space-y-1 z-10">
                        <div class="w-10 h-10 rounded-full bg-white/20 border border-white/30 flex items-center justify-center text-lg mx-auto shadow-md">
                            💬
                        </div>
                        <p class="text-white font-extrabold text-[11px] uppercase tracking-widest mt-1">Bisik Rahasia</p>
                    </div>

                    <!-- Prompt Card -->
                    <div class="bg-white rounded-2xl p-4 shadow-xl z-10 space-y-3 transform -rotate-1 border border-pink-100/50">
                        <div class="flex items-center gap-2 justify-center">
                            <span class="text-xs">🤫</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Kirim Pesan Anonim!</span>
                        </div>
                        <p class="text-slate-800 font-black text-sm leading-tight">
                            "Kirim aku curhatan jujur atau rahasia terbesarmu! 100% aman & anonim"
                        </p>
                        <div class="w-full py-2 bg-slate-100 rounded-lg text-[9px] text-slate-400 font-bold">
                            Tulis bisikan rahasia di sini...
                        </div>
                    </div>

                    <!-- Subtext / Call-to-action in phone -->
                    <div class="z-10 bg-slate-950/20 backdrop-blur-md rounded-full py-1.5 px-3 border border-white/10 mx-auto">
                        <p class="text-white font-bold text-[9px] tracking-wider uppercase">
                            🔒 100% Anonim & Rahasia
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Styling Overrides */
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
        animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
