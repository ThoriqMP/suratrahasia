@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-12 px-4 animate-fade-in-up">
    <div class="text-center mb-10">
        <div class="inline-block p-4 rounded-full bg-purple-500/10 border border-purple-500/20 mb-6 shadow-[0_0_30px_rgba(168,85,247,0.2)]">
            <span class="text-4xl filter drop-shadow-lg">✨</span>
        </div>
        <h2 class="text-3xl font-black text-white tracking-tight mb-2">Buat Akun Baru</h2>
        <p class="text-slate-400 text-sm font-medium">Dapatkan 1 Kredit Gratis untuk mencoba!</p>
    </div>

    <div class="glass-card p-8 md:p-10 relative overflow-hidden">
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm font-bold">
                @foreach ($errors->all() as $error)
                    <p>⚠️ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            
            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-2 uppercase tracking-widest group-focus-within:text-purple-400 transition-colors">Nama Panggilan</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none"
                       placeholder="Misal: Dilan" required autofocus>
            </div>

            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-2 uppercase tracking-widest group-focus-within:text-purple-400 transition-colors">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none"
                       placeholder="nama@email.com" required>
            </div>

            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-2 uppercase tracking-widest group-focus-within:text-purple-400 transition-colors">No WhatsApp (Opsional)</label>
                <input type="text" name="no_wa" value="{{ old('no_wa') }}"
                       class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none"
                       placeholder="08123456789">
            </div>

            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-2 uppercase tracking-widest group-focus-within:text-purple-400 transition-colors">Password</label>
                <input type="password" name="password"
                       class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none"
                       placeholder="Minimal 8 karakter" required>
            </div>
            
            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-2 uppercase tracking-widest group-focus-within:text-purple-400 transition-colors">Ulangi Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none"
                       placeholder="Ketik ulang password" required>
            </div>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-black text-lg py-4 rounded-2xl flex items-center justify-center shadow-xl mt-8 transition-all hover:-translate-y-1 hover:shadow-purple-500/30">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-8 text-center border-t border-white/10 pt-6">
            <p class="text-slate-400 text-sm">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-purple-400 font-bold hover:text-purple-300 transition-colors">Masuk di sini</a>
            </p>
        </div>
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
