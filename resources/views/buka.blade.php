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
}" class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="text-center mb-8 animate-fade-in-down">
            <div class="text-6xl mb-4 animate-bounce">🔒</div>
            <h2 class="text-3xl font-bold text-pink-600 mb-2">Surat Rahasia</h2>
            <p class="text-gray-600">Untuk: {{ $surat->untuk }}</p>
        </div>

        <form x-ref="form" method="POST" action="/surat/{{ $surat->kode }}" 
              @submit.prevent="submitForm" 
              class="space-y-6 bg-white p-8 rounded-2xl shadow-lg border border-pink-100">
            @csrf

            <div>
                <input x-ref="password" 
                       type="password" 
                       name="password" 
                       placeholder="Masukkan Password"
                       class="w-full px-4 py-3 rounded-lg border-2 border-pink-200 focus:border-pink-400 
                              focus:ring-2 focus:ring-pink-200 transition-all duration-200"
                       :disabled="loading">
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-pink-500 to-rose-500 text-white py-3 rounded-lg 
                           font-semibold transition-all duration-200 hover:scale-[1.02] relative"
                    :disabled="loading">
                <span x-show="!loading">Buka Surat ✉️</span>
                <span x-show="loading" class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Membuka...
                </span>
            </button>

            <div x-show="error" class="text-red-500 text-center animate-shake">
                Password salah! Coba lagi
            </div>
        </form>
    </div>
</div>

<style>
    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out;
    }
    
    .animate-shake {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes shake {
        10%, 90% { transform: translateX(-1px); }
        20%, 80% { transform: translateX(2px); }
        30%, 50%, 70% { transform: translateX(-4px); }
        40%, 60% { transform: translateX(4px); }
    }
</style>
@endsection