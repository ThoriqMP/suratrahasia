@extends('layouts.app')

@section('content')
<div x-data="{ 
    editOpen: false, 
    editSuratId: null, 
    editSuratUntuk: '', 
    editAction: '',
    openEdit(id, untuk, actionUrl) {
        this.editSuratId = id;
        this.editSuratUntuk = untuk;
        this.editAction = actionUrl;
        this.editOpen = true;
    }
}" class="max-w-4xl mx-auto py-8 animate-fade-in-up">

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-bold">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Header -->
    <div class="glass-card p-8 mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-pink-500/20 border-2 border-pink-500/30 flex items-center justify-center text-3xl">
                👤
            </div>
            <div>
                <h2 class="text-2xl font-black text-white">{{ $user->name }}</h2>
                <p class="text-slate-400">{{ $user->email }}</p>
            </div>
        </div>
        
        <div class="text-center md:text-right">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Sisa Kredit</p>
            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-400">
                {{ $user->credits }} 💎
            </div>
            <div class="mt-3 flex flex-wrap justify-center md:justify-end gap-2">
                <a href="{{ route('topup.form') }}" class="inline-block bg-white/10 hover:bg-white/20 text-white text-sm font-bold py-2 px-4 rounded-xl border border-white/20 transition-all">
                    + Top Up Kredit
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 text-sm font-bold py-2 px-4 rounded-xl border border-rose-500/30 transition-all flex items-center gap-1">
                        <span>🚪</span> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Letter History -->
    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span>💌</span> Riwayat Surat Kamu</h3>
    
    @if($surats->isEmpty())
        <div class="glass-card p-10 text-center">
            <span class="text-4xl mb-4 block opacity-50">📭</span>
            <p class="text-slate-400 font-medium">Kamu belum membuat surat apapun.</p>
            <a href="/create" class="mt-4 inline-block btn-immersive text-white font-bold py-2 px-6 rounded-xl">Buat Surat Pertama</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($surats as $surat)
                <div class="glass-card p-6 relative group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-pink-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Untuk</p>
                                <p class="text-lg font-bold text-white">{{ $surat->untuk }}</p>
                            </div>
                            <span class="px-2 py-1 bg-white/10 rounded text-xs text-slate-300 font-mono">{{ $surat->kode }}</span>
                        </div>
                        <p class="text-sm text-slate-400 line-clamp-2 mb-4">{{ $surat->isi }}</p>
                        
                        <div class="flex items-center justify-between border-t border-white/10 pt-4">
                            <span class="text-xs text-slate-500">{{ $surat->created_at->diffForHumans() }}</span>
                            <div class="flex gap-3">
                                <button @click="openEdit({{ $surat->id }}, '{{ addslashes($surat->untuk) }}', '{{ route('surat.update-password', $surat->id) }}')" 
                                        class="text-xs font-bold text-purple-400 hover:text-purple-300 flex items-center gap-1 transition-all">
                                    <span>🔑</span> Edit Sandi
                                </button>
                                <a href="/surat/{{ $surat->kode }}" target="_blank" class="text-xs font-bold text-pink-400 hover:text-pink-300">Lihat Surat ↗</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <!-- Edit Password Modal -->
    <div x-show="editOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        <div class="glass-card w-full max-w-md p-8 border border-white/20 shadow-2xl relative" @click.away="editOpen = false">
            <button @click="editOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-white text-xl">&times;</button>
            <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-2"><span>🔑</span> Edit Password Surat</h3>
            <p class="text-xs text-slate-400 mb-6">Ubah password untuk surat yang ditujukan kepada <span class="text-pink-400 font-bold" x-text="editSuratUntuk"></span>.</p>
            
            <form :action="editAction" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-widest mb-2">Password Baru</label>
                    <input type="password" name="password" placeholder="Masukkan password baru" required minlength="3"
                           class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl focus:border-pink-500 focus:ring-1 focus:ring-pink-500/20 text-white text-sm outline-none transition-all">
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="editOpen = false"
                            class="flex-1 py-3 bg-white/5 hover:bg-white/10 text-white text-sm font-bold rounded-xl border border-white/10 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 btn-immersive text-white text-sm font-bold rounded-xl transition-all">
                        Simpan
                    </button>
                </div>
            </form>
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

@once
@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endonce
@endsection
