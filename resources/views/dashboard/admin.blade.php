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
</div>

<style>
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
