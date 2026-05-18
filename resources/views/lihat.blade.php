@extends('layouts.app')

@section('content')
<div x-data="{
    showConfetti: {{ request()->has('unlocked') ? 'true' : 'false' }}
}" 
     x-init="if(showConfetti) { $nextTick(() => playConfetti()) }"
     class="max-w-3xl mx-auto px-2 py-8 md:py-12">
     
    <div class="animate-fade-in-up">
        <!-- Letter Header -->
        <div class="text-center mb-12">
            <div class="inline-block p-6 rounded-full bg-pink-500/10 border border-pink-500/20 mb-6 shadow-[0_0_60px_rgba(244,114,182,0.4)] animate-heart-beat">
                <span class="text-6xl filter drop-shadow-2xl">💖</span>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-300 uppercase tracking-widest mb-3">Dari: <span class="text-white">{{ $surat->dari }}</span></h2>
            <p class="text-xl md:text-2xl font-medium text-pink-400">Untuk: <span class="font-bold text-white">{{ $surat->untuk }}</span></p>
        </div>

        <!-- The Letter Content -->
        <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-b from-pink-500/20 to-purple-500/20 rounded-[32px] blur-xl group-hover:blur-2xl transition-all duration-500 opacity-50"></div>
            <div class="relative bg-white/10 backdrop-blur-2xl p-8 md:p-14 rounded-[32px] border border-white/20 shadow-2xl">
                <div class="prose prose-invert prose-pink max-w-none text-justify whitespace-pre-wrap text-lg md:text-xl leading-relaxed text-slate-200 font-medium">
                    {{ $surat->isi }}
                </div>
            </div>
        </div>

        <!-- Letter Footer -->
        <div class="mt-12 text-center flex flex-col items-center">
            <div class="w-16 h-1 bg-gradient-to-r from-transparent via-pink-500 to-transparent mb-6 opacity-50"></div>
            <p class="text-sm font-black text-slate-500 uppercase tracking-[0.3em]">Dibuka dengan cinta</p>
        </div>
    </div>
</div>

<script>
function playConfetti() {
    const count = 300;
    const defaults = {
        origin: { y: 0.7 },
        colors: ['#f472b6', '#a78bfa', '#fb7185', '#ffffff']
    };

    function fire(particleRatio, opts) {
        confetti(Object.assign({}, defaults, opts, {
            particleCount: Math.floor(count * particleRatio)
        }));
    }

    fire(0.25, { spread: 26, startVelocity: 55 });
    fire(0.2, { spread: 60 });
    fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
    fire(0.1, { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
    fire(0.1, { spread: 120, startVelocity: 45 });
}
</script>

<style>
.animate-fade-in-up {
    animation: fadeInUp 1s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-heart-beat {
    animation: heartBeat 2s ease-in-out infinite;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes heartBeat {
    0% { transform: scale(1); }
    15% { transform: scale(1.15); }
    30% { transform: scale(1); }
    45% { transform: scale(1.08); }
    60% { transform: scale(1); }
}

/* Enhancing prose for the letter */
.prose { color: #e2e8f0; }
.prose p { margin-bottom: 1.5em; }
</style>

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endonce
@endsection