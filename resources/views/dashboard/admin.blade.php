@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 animate-fade-in-up">
    <!-- Header -->
    <div class="glass-card p-8 mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-white flex items-center gap-2"><span>🛡️</span> Admin Panel</h2>
            <p class="text-slate-400">Kelola persetujuan top-up dan pantau sistem.</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-emerald-400 uppercase tracking-widest">Administrator</p>
            <p class="text-white">{{ $user->name }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-bold">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Pending Transactions -->
    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span>💰</span> Menunggu Persetujuan ({{ $pendingTransactions->count() }})</h3>
    
    <div class="glass-card overflow-hidden mb-12">
        @if($pendingTransactions->isEmpty())
            <div class="p-8 text-center text-slate-400">Tidak ada transaksi yang menunggu persetujuan.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-white/5 border-b border-white/10 uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-6 py-4">Invoice</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Kredit</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($pendingTransactions as $trx)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 font-mono text-white">{{ $trx->kode_invoice }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-white">{{ $trx->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $trx->user->email }}</p>
                                </td>
                                <td class="px-6 py-4 font-bold text-pink-400">+{{ $trx->jumlah_kredit }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('admin.approve', $trx->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/40 px-3 py-1 rounded font-bold transition-all">
                                            Approve
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Package Editor -->
    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2 mt-12"><span>🏷️</span> Kelola Paket Kredit</h3>
    <p class="text-slate-400 mb-6 text-sm">Sesuaikan harga dan detail paket kredit yang ditawarkan kepada pengguna.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($packages as $paket)
        <div class="glass-card p-6 relative">
            @if($paket->is_popular)
                <div class="absolute top-0 right-0 bg-gradient-to-r from-emerald-500 to-teal-500 text-[10px] font-bold text-white px-8 py-1 rotate-45 translate-x-[25px] translate-y-[10px] shadow-md">POPULER</div>
            @endif
            
            <form method="POST" action="{{ route('admin.package.update', $paket->id) }}" class="space-y-4">
                @csrf
                <div class="group">
                    <label class="block text-xs font-bold text-slate-400 mb-1 uppercase">Nama Paket</label>
                    <input type="text" name="nama_paket" value="{{ $paket->nama_paket }}"
                           class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 text-white text-sm outline-none transition-all" required>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="group">
                        <label class="block text-xs font-bold text-slate-400 mb-1 uppercase">Jumlah Kredit</label>
                        <input type="number" name="jumlah_kredit" value="{{ $paket->jumlah_kredit }}" min="1"
                               class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 text-white text-sm outline-none transition-all" required>
                    </div>
                    <div class="group">
                        <label class="block text-xs font-bold text-slate-400 mb-1 uppercase">Harga (Rp)</label>
                        <input type="number" name="harga" value="{{ $paket->harga }}" min="0"
                               class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 text-white text-sm outline-none transition-all" required>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_popular" id="popular_{{ $paket->id }}" class="w-4 h-4 text-emerald-500 bg-slate-900 border-white/20 rounded focus:ring-emerald-500 focus:ring-2" {{ $paket->is_popular ? 'checked' : '' }}>
                    <label for="popular_{{ $paket->id }}" class="text-sm text-slate-300">Tandai sebagai Populer</label>
                </div>

                <button type="submit" class="w-full mt-4 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/40 py-2 rounded-xl font-bold transition-all text-sm">
                    Simpan Perubahan
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>

<style>
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Admin Panel Specific Overrides */
    body {
        background-color: #022c22 !important; /* Deep Emerald */
    }
    
    .mesh-gradient {
        background-image: 
            radial-gradient(at 0% 0%, hsla(160, 100%, 20%, 1) 0, transparent 50%), 
            radial-gradient(at 50% 0%, hsla(180, 100%, 15%, 1) 0, transparent 50%), 
            radial-gradient(at 100% 0%, hsla(140, 100%, 20%, 1) 0, transparent 50%), 
            radial-gradient(at 0% 100%, hsla(200, 100%, 15%, 1) 0, transparent 50%) !important;
    }

    .glass-card {
        border: 1px solid rgba(16, 185, 129, 0.2) !important;
        box-shadow: 0 4px 30px rgba(16, 185, 129, 0.05) !important;
    }

    .glass-nav {
        border: 1px solid rgba(16, 185, 129, 0.2) !important;
        background: rgba(2, 44, 34, 0.8) !important;
    }
</style>
@endsection
