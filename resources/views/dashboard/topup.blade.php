@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 animate-fade-in-up">
    <div class="text-center mb-10">
        <div class="inline-block p-4 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-6 shadow-[0_0_30px_rgba(16,185,129,0.2)]">
            <span class="text-4xl filter drop-shadow-lg">💎</span>
        </div>
        <h2 class="text-3xl font-black text-white tracking-tight mb-2">Top Up Kredit</h2>
        <p class="text-slate-400">Beli kredit untuk mengirim lebih banyak surat cinta dengan desain premium.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm font-bold">
            ⚠️ Pilih salah satu paket terlebih dahulu.
        </div>
    @endif

    <form method="POST" action="{{ route('topup.process') }}" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Paket 1 -->
            <label class="cursor-pointer relative">
                <input type="radio" name="paket" value="1" class="peer sr-only" required>
                <div class="glass-card p-6 text-center border-2 border-transparent peer-checked:border-pink-500 peer-checked:bg-pink-500/10 transition-all group">
                    <span class="text-3xl block mb-2">💌</span>
                    <h3 class="text-xl font-bold text-white mb-1">1 Kredit</h3>
                    <p class="text-pink-400 font-black text-lg">Rp 1.000</p>
                </div>
                <div class="absolute top-2 right-2 w-5 h-5 rounded-full border-2 border-white/20 peer-checked:border-pink-500 peer-checked:bg-pink-500 flex items-center justify-center transition-all"></div>
            </label>

            <!-- Paket 2 -->
            <label class="cursor-pointer relative">
                <input type="radio" name="paket" value="5" class="peer sr-only" required>
                <div class="glass-card p-6 text-center border-2 border-transparent peer-checked:border-pink-500 peer-checked:bg-pink-500/10 transition-all group">
                    <span class="text-3xl block mb-2">💐</span>
                    <h3 class="text-xl font-bold text-white mb-1">5 Kredit</h3>
                    <p class="text-pink-400 font-black text-lg">Rp 5.000</p>
                </div>
                <div class="absolute top-2 right-2 w-5 h-5 rounded-full border-2 border-white/20 peer-checked:border-pink-500 peer-checked:bg-pink-500 flex items-center justify-center transition-all"></div>
            </label>

            <!-- Paket 3 -->
            <label class="cursor-pointer relative">
                <input type="radio" name="paket" value="15" class="peer sr-only" required>
                <div class="glass-card p-6 text-center border-2 border-transparent peer-checked:border-pink-500 peer-checked:bg-pink-500/10 transition-all group relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-gradient-to-r from-pink-500 to-purple-500 text-[10px] font-bold text-white px-8 py-1 rotate-45 translate-x-[25px] translate-y-[10px]">POPULER</div>
                    <span class="text-3xl block mb-2">🎁</span>
                    <h3 class="text-xl font-bold text-white mb-1">15 Kredit</h3>
                    <p class="text-pink-400 font-black text-lg">Rp 10.000</p>
                    <p class="text-xs text-slate-400 line-through">Rp 15.000</p>
                </div>
                <div class="absolute top-2 right-2 w-5 h-5 rounded-full border-2 border-white/20 peer-checked:border-pink-500 peer-checked:bg-pink-500 flex items-center justify-center transition-all"></div>
            </label>

            <!-- Paket 4 -->
            <label class="cursor-pointer relative">
                <input type="radio" name="paket" value="35" class="peer sr-only" required>
                <div class="glass-card p-6 text-center border-2 border-transparent peer-checked:border-pink-500 peer-checked:bg-pink-500/10 transition-all group">
                    <span class="text-3xl block mb-2">👑</span>
                    <h3 class="text-xl font-bold text-white mb-1">35 Kredit</h3>
                    <p class="text-pink-400 font-black text-lg">Rp 20.000</p>
                    <p class="text-xs text-slate-400 line-through">Rp 35.000</p>
                </div>
                <div class="absolute top-2 right-2 w-5 h-5 rounded-full border-2 border-white/20 peer-checked:border-pink-500 peer-checked:bg-pink-500 flex items-center justify-center transition-all"></div>
            </label>
        </div>

        <div class="glass-card p-6 text-slate-300 text-sm">
            <h4 class="font-bold text-white mb-2 flex items-center gap-2"><span>ℹ️</span> Informasi Pembayaran</h4>
            <p class="mb-2">Setelah menekan tombol di bawah, kamu akan diarahkan ke WhatsApp untuk melakukan konfirmasi dengan Admin.</p>
            <p>Admin akan memberikan instruksi pembayaran (Gopay / Dana / ShopeePay) melalui WhatsApp.</p>
        </div>

        <button type="submit"
                class="w-full btn-immersive text-white font-black text-lg py-4 rounded-2xl flex items-center justify-center gap-2 shadow-xl">
            Lanjut ke WhatsApp <span class="text-2xl">📱</span>
        </button>
    </form>
    
    <div class="mt-6 text-center">
        <a href="/dashboard" class="text-slate-400 hover:text-white font-bold text-sm transition-colors">Batal & Kembali</a>
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
