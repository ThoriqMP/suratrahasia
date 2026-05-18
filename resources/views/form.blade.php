@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="text-center mb-10">
        <div class="inline-block p-4 rounded-full bg-pink-500/20 border border-pink-500/30 mb-4 animate-pulse">
            <span class="text-3xl">✍️</span>
        </div>
        <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">
            Tulis Surat Cinta
        </h2>
        <p class="text-lg font-medium text-pink-400">Ungkapkan perasaanmu, buat dia tersenyum.</p>
    </div>

    <form action="/surat" method="POST" class="space-y-6">
        @csrf
        
        <div class="space-y-6">
            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-2 group-focus-within:text-pink-400 transition-colors uppercase tracking-widest">👤 Dari</label>
                <input type="text" name="dari" placeholder="Nama kamu" 
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-pink-500 
                           focus:ring-2 focus:ring-pink-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none"
                    required>
            </div>

            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-2 group-focus-within:text-pink-400 transition-colors uppercase tracking-widest">💖 Untuk</label>
                <input type="text" name="untuk" placeholder="Nama si dia" 
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-pink-500 
                           focus:ring-2 focus:ring-pink-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none"
                    required>
            </div>

            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-2 group-focus-within:text-pink-400 transition-colors uppercase tracking-widest">💌 Isi Surat</label>
                <textarea name="isi" rows="6" placeholder="Tuliskan isi hati kamu sejujur-jujurnya..." 
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-pink-500 
                           focus:ring-2 focus:ring-pink-500/20 focus:bg-white/10 text-white placeholder-slate-500 resize-y min-h-[150px] transition-all duration-300 outline-none"
                    required></textarea>
            </div>

            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-2 group-focus-within:text-pink-400 transition-colors uppercase tracking-widest">🔑 Password Rahasia</label>
                <input type="password" name="password" placeholder="Buat password untuk membuka surat ini" 
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-pink-500 
                           focus:ring-2 focus:ring-pink-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none"
                    required>
                <p class="mt-2 text-xs text-slate-500">Beritahu dia password ini saat kamu membagikan linknya nanti.</p>
            </div>

            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-2 group-focus-within:text-pink-400 transition-colors uppercase tracking-widest">⏳ Waktu Kedaluwarsa (Hari)</label>
                <input type="number" name="waktu_hapus" min="1" max="30" placeholder="Misal: 7 (opsional)" 
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-pink-500 
                           focus:ring-2 focus:ring-pink-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none">
                <p class="mt-2 text-xs text-slate-500">Berapa lama surat ini bertahan? (Default: 7 hari setelah dibuka).</p>
            </div>
            <div class="group">
                <label class="block text-sm font-bold text-slate-300 mb-4 uppercase tracking-widest group-focus-within:text-pink-400 transition-colors">🎨 Pilih Tema Desain (Premium)</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="tema_desain" value="classic" class="peer sr-only" checked>
                        <div class="glass-card p-4 text-center border-2 border-transparent peer-checked:border-pink-500 peer-checked:bg-pink-500/10 transition-all rounded-xl">
                            <span class="text-3xl block mb-2">🌸</span>
                            <p class="font-bold text-white text-sm">Classic Pink</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tema_desain" value="neon" class="peer sr-only">
                        <div class="glass-card p-4 text-center border-2 border-transparent peer-checked:border-purple-500 peer-checked:bg-purple-500/10 transition-all rounded-xl">
                            <span class="text-3xl block mb-2">⚡</span>
                            <p class="font-bold text-white text-sm">Dark Neon</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tema_desain" value="vintage" class="peer sr-only">
                        <div class="glass-card p-4 text-center border-2 border-transparent peer-checked:border-amber-500 peer-checked:bg-amber-500/10 transition-all rounded-xl">
                            <span class="text-3xl block mb-2">📜</span>
                            <p class="font-bold text-white text-sm">Vintage Paper</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" 
            class="w-full btn-immersive text-white font-black text-lg py-5 px-6 rounded-2xl mt-8 flex items-center justify-center gap-2">
            <span>🚀</span> Kirimkan Perasaan Ini (-1 Kredit)
        </button>
    </form>
</div>
@endsection
