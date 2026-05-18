@extends('layouts.app')

@section('content')
<div x-data="{
    loading: false,
    error: @entangle('error').defer,
    async submitForm() {
        this.loading = true;
        this.error = false;
        
        const form = this.$refs.form;
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                password: this.$refs.password.value
            })
        });

        if (response.ok) {
            window.location.href = response.url + '?unlocked=1';
        } else {
            this.error = true;
            this.loading = false;
        }
    }
}" class="min-h-[70vh] flex flex-col items-center justify-center">

    <div class="w-full max-w-md px-4">
        <div class="text-center mb-10 animate-fade-in-down">
            <div class="inline-block p-5 rounded-full bg-white/5 border border-white/10 mb-6 shadow-[0_0_50px_rgba(244,114,182,0.3)] animate-bounce-slow backdrop-blur-md">
                <span class="text-5xl">🔒</span>
            </div>
            <h2 class="text-3xl font-black text-white mb-2 tracking-widest uppercase">Surat Rahasia</h2>
            <p class="text-pink-400 font-medium">Teruntuk: <span class="text-white font-bold">{{ $surat->untuk }}</span></p>
        </div>

        <form x-ref="form" method="POST" action="/surat/{{ $surat->kode }}" 
              @submit.prevent="submitForm" 
              class="space-y-6">
            @csrf

            <div class="group">
                <input x-ref="password" 
                       type="password" 
                       name="password" 
                       placeholder="Masukkan Password Rahasia"
                       class="w-full px-6 py-5 rounded-2xl bg-white/5 border-2 border-white/10 text-white placeholder-slate-500 text-center text-lg tracking-widest focus:border-pink-500 focus:bg-white/10 focus:ring-4 focus:ring-pink-500/20 transition-all duration-300 outline-none"
                       :disabled="loading">
            </div>

            <button type="submit" 
                    class="w-full btn-immersive text-white py-5 rounded-2xl font-black text-lg transition-all duration-300 shadow-xl"
                    :disabled="loading">
                <span x-show="!loading" class="flex items-center justify-center gap-2">Buka Surat <span>💌</span></span>
                <span x-show="loading" class="flex items-center justify-center">
                    <svg class="animate-spin h-6 w-6 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Membuka...
                </span>
            </button>

            <div x-show="error" class="mt-4 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-center font-bold animate-shake backdrop-blur-sm" x-cloak>
                ⚠️ Password salah! Coba lagi.
            </div>
        </form>
    </div>
</div>

<style>
    .animate-fade-in-down {
        animation: fadeInDown 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .animate-bounce-slow {
        animation: bounce 3s infinite ease-in-out;
    }
    .animate-shake {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes shake {
        10%, 90% { transform: translateX(-2px); }
        20%, 80% { transform: translateX(3px); }
        30%, 50%, 70% { transform: translateX(-5px); }
        40%, 60% { transform: translateX(5px); }
    }
</style>
@endsection