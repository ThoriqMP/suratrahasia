@extends('layouts.app')

@section('content')
<div x-data="{
    showConfetti: {{ request()->has('unlocked') ? 'true' : 'false' }}
}" 
     x-init="if(showConfetti) { $nextTick(() => playConfetti()) }"
     class="max-w-2xl mx-auto px-4 py-12">
    <div class="animate-fade-in-up">
        <div class="text-center mb-8">
            <div class="text-6xl mb-4 animate-heart-beat">💖</div>
            <h2 class="text-3xl font-bold text-pink-600">Dari: {{ $surat->dari }}</h2>
            <p class="text-gray-600 mt-2">Untuk: {{ $surat->untuk }}</p>
        </div>

        <div class="bg-pink-50 p-8 rounded-2xl shadow-lg border-2 border-pink-100">
            <div class="prose prose-pink max-w-none text-justify whitespace-pre-wrap">
                {{ $surat->isi }}
            </div>
        </div>

        <p class="text-center text-pink-400 mt-8">💌 Dibuka dengan cinta</p>
    </div>
</div>

<script>
function playConfetti() {
    const count = 200;
    const defaults = {
        origin: { y: 0.7 }
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
    animation: fadeInUp 0.8s ease-out;
}

.animate-heart-beat {
    animation: heartBeat 1s ease-in-out;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes heartBeat {
    0% { transform: scale(1); }
    15% { transform: scale(1.3); }
    30% { transform: scale(1); }
    45% { transform: scale(1.15); }
    60% { transform: scale(1); }
}
</style>

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endonce
@endsection