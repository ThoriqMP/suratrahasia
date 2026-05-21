@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ 
    selectedTema: 'classic',
    previewOpen: false,
    previewDari: '',
    previewUntuk: '',
    previewIsi: '',
    showPreview() {
        this.previewDari = document.querySelector('input[name=dari]').value || 'Pengagum Rahasiamu';
        this.previewUntuk = document.querySelector('input[name=untuk]').value || 'Si Dia';
        this.previewIsi = document.querySelector('textarea[name=isi]').value || 'Tuliskan isi hati kamu sejujur-jujurnya di sini...';
        this.previewOpen = true;
    }
}">
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
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-bold text-slate-300 uppercase tracking-widest group-focus-within:text-pink-400 transition-colors">🎨 Pilih Tema Desain (Premium)</label>
                    <button type="button" @click="showPreview()" 
                            class="px-4 py-1.5 bg-pink-500/10 hover:bg-pink-500/20 text-pink-300 text-xs font-bold rounded-xl border border-pink-500/20 transition-all flex items-center gap-1">
                        <span>👁️</span> Pratinjau Tema
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="cursor-pointer" @click="selectedTema = 'classic'">
                        <input type="radio" name="tema_desain" value="classic" class="sr-only" x-model="selectedTema">
                        <div class="glass-card p-4 text-center border-2 transition-all rounded-xl"
                             :class="selectedTema === 'classic' ? 'border-pink-500 bg-pink-500/10' : 'border-transparent'">
                            <span class="text-3xl block mb-2">🌸</span>
                            <p class="font-bold text-white text-sm">Classic Pink</p>
                        </div>
                    </label>
                    <label class="cursor-pointer" @click="selectedTema = 'neon'">
                        <input type="radio" name="tema_desain" value="neon" class="sr-only" x-model="selectedTema">
                        <div class="glass-card p-4 text-center border-2 transition-all rounded-xl"
                             :class="selectedTema === 'neon' ? 'border-purple-500 bg-purple-500/10' : 'border-transparent'">
                            <span class="text-3xl block mb-2">⚡</span>
                            <p class="font-bold text-white text-sm">Dark Neon</p>
                        </div>
                    </label>
                    <label class="cursor-pointer" @click="selectedTema = 'vintage'">
                        <input type="radio" name="tema_desain" value="vintage" class="sr-only" x-model="selectedTema">
                        <div class="glass-card p-4 text-center border-2 transition-all rounded-xl"
                             :class="selectedTema === 'vintage' ? 'border-amber-500 bg-amber-500/10' : 'border-transparent'">
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
    <!-- Preview Theme Modal -->
    <div x-show="previewOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md"
         x-transition
         x-cloak>
        <div class="w-full max-w-2xl bg-slate-900/90 p-6 md:p-8 rounded-[36px] border border-white/10 shadow-2xl relative" @click.away="previewOpen = false">
            <div class="flex justify-between items-center mb-6 border-b border-white/10 pb-4">
                <span class="text-sm font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <span>👁️</span> Pratinjau Tema: <span class="text-pink-400" x-text="selectedTema.toUpperCase()"></span>
                </span>
                <button type="button" @click="previewOpen = false" class="bg-white/5 hover:bg-white/10 text-white font-bold py-1.5 px-4 rounded-xl text-sm border border-white/10 transition-all">
                    Tutup
                </button>
            </div>
            
            <div class="max-h-[60vh] overflow-y-auto pr-2">
                <!-- Mock Letter header matching lihat.blade.php exactly -->
                <div class="text-center mb-8">
                    <div class="inline-block p-4 rounded-full bg-pink-500/10 border border-pink-500/20 mb-3 animate-pulse">
                        <span class="text-4xl">💖</span>
                    </div>
                    <h2 class="text-lg md:text-xl font-black text-slate-300 uppercase tracking-widest">Dari: <span class="text-white" x-text="previewDari"></span></h2>
                    <p class="text-sm md:text-base font-medium text-pink-400">Untuk: <span class="font-bold text-white" x-text="previewUntuk"></span></p>
                </div>
                
                <div class="relative group">
                    <!-- Neon Preview -->
                    <template x-if="selectedTema === 'neon'">
                        <div>
                            <div class="absolute inset-0 bg-gradient-to-b from-cyan-500/30 to-purple-500/30 rounded-[32px] blur-xl opacity-70"></div>
                            <div class="relative bg-slate-900/80 backdrop-blur-2xl p-8 md:p-12 rounded-[32px] border border-cyan-500/30 shadow-[0_0_50px_rgba(6,182,212,0.2)]">
                                <div class="max-w-none text-justify whitespace-pre-wrap text-base md:text-lg leading-relaxed text-cyan-50 font-medium font-mono" x-text="previewIsi"></div>
                            </div>
                        </div>
                    </template>

                    <!-- Vintage Preview -->
                    <template x-if="selectedTema === 'vintage'">
                        <div>
                            <div class="absolute inset-0 bg-gradient-to-b from-amber-500/20 to-orange-500/20 rounded-[32px] blur-xl opacity-50"></div>
                            <div class="relative bg-[#fdf6e3] backdrop-blur-2xl p-8 md:p-12 rounded-[32px] border border-amber-900/20 shadow-2xl">
                                <div class="max-w-none text-justify whitespace-pre-wrap text-base md:text-lg leading-relaxed text-amber-900 font-serif" x-text="previewIsi"></div>
                            </div>
                        </div>
                    </template>

                    <!-- Classic Pink Preview -->
                    <template x-if="selectedTema === 'classic'">
                        <div>
                            <div class="absolute inset-0 bg-gradient-to-b from-pink-500/20 to-purple-500/20 rounded-[32px] blur-xl opacity-50"></div>
                            <div class="relative bg-white/10 backdrop-blur-2xl p-8 md:p-12 rounded-[32px] border border-white/20 shadow-2xl">
                                <div class="max-w-none text-justify whitespace-pre-wrap text-base md:text-lg leading-relaxed text-slate-200 font-medium" x-text="previewIsi"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endonce
@endsection
