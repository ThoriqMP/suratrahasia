@extends('layouts.app')

@section('content')
<div x-data="{ 
    activeTab: 'overview',
    modalOpen: false,
    modalUserId: null,
    modalUserName: '',
    modalUserCredits: 0,
    modalAction: '',
    openModal(id, name, credits, actionUrl) {
        this.modalUserId = id;
        this.modalUserName = name;
        this.modalUserCredits = credits;
        this.modalAction = actionUrl;
        this.modalOpen = true;
    }
}" class="max-w-6xl mx-auto py-4 animate-fade-in-up">

    <!-- Header Section -->
    <div class="glass-card p-6 md:p-8 mb-8 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden border-emerald-500/20 shadow-[0_0_50px_rgba(16,185,129,0.05)]">
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 to-transparent pointer-events-none"></div>
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 flex items-center justify-center text-3xl shadow-lg shadow-emerald-500/10">
                🛡️
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight">Admin Dashboard</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-[10px] font-bold text-emerald-400 uppercase tracking-widest">SysAdmin</span>
                </div>
                <p class="text-slate-400 text-sm mt-0.5">Kelola transaksi, pantau aktivitas pengguna, dan kendalikan sistem.</p>
            </div>
        </div>
        <div class="text-center md:text-right relative z-10 bg-slate-900/40 px-5 py-3 rounded-2xl border border-white/5">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Administrator</p>
            <p class="text-white font-extrabold text-lg mt-0.5">{{ $user->name }}</p>
            <p class="text-slate-500 text-xs font-mono">{{ $user->email }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-bold flex items-center gap-2 animate-bounce-subtle">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 font-bold flex items-center gap-2">
            <span>❌</span> {{ session('error') }}
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="flex overflow-x-auto pb-2 mb-8 gap-2 border-b border-white/10 scrollbar-none">
        <button @click="activeTab = 'overview'" 
                :class="activeTab === 'overview' ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-400 shadow-lg' : 'bg-white/5 border-transparent text-slate-400 hover:text-white hover:bg-white/10'" 
                class="px-5 py-3 rounded-xl border font-bold text-sm transition-all duration-300 flex items-center gap-2 shrink-0">
            <span>📊</span> Ringkasan Sistem
        </button>
        <button @click="activeTab = 'users'" 
                :class="activeTab === 'users' ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-400 shadow-lg' : 'bg-white/5 border-transparent text-slate-400 hover:text-white hover:bg-white/10'" 
                class="px-5 py-3 rounded-xl border font-bold text-sm transition-all duration-300 flex items-center gap-2 shrink-0 relative">
            <span>👥</span> Pengguna Aktif
            <span class="px-1.5 py-0.5 rounded-full bg-slate-800 text-[10px] text-slate-300 font-bold font-mono">{{ $users->count() }}</span>
        </button>
        <button @click="activeTab = 'transactions'" 
                :class="activeTab === 'transactions' ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-400 shadow-lg' : 'bg-white/5 border-transparent text-slate-400 hover:text-white hover:bg-white/10'" 
                class="px-5 py-3 rounded-xl border font-bold text-sm transition-all duration-300 flex items-center gap-2 shrink-0 relative">
            <span>💰</span> Menunggu Persetujuan
            @if($pendingTransactions->count() > 0)
                <span class="px-2 py-0.5 rounded-full bg-amber-500 text-[10px] text-slate-950 font-black animate-pulse font-mono">{{ $pendingTransactions->count() }}</span>
            @else
                <span class="px-1.5 py-0.5 rounded-full bg-slate-800 text-[10px] text-slate-300 font-bold font-mono">0</span>
            @endif
        </button>
        <button @click="activeTab = 'packages'" 
                :class="activeTab === 'packages' ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-400 shadow-lg' : 'bg-white/5 border-transparent text-slate-400 hover:text-white hover:bg-white/10'" 
                class="px-5 py-3 rounded-xl border font-bold text-sm transition-all duration-300 flex items-center gap-2 shrink-0">
            <span>🏷️</span> Paket Kredit
        </button>
    </div>

    <!-- TAB CONTENT: OVERVIEW -->
    <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <!-- Statistics Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
            <!-- Stat Card 1 -->
            <div class="glass-card p-6 border-emerald-500/10 flex flex-col justify-between hover:border-emerald-500/30 hover:scale-[1.02] transition-all duration-300">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Pengguna</p>
                <div class="flex items-baseline justify-between mt-4">
                    <span class="text-3xl md:text-4xl font-black text-white font-mono">{{ $stats['total_users'] }}</span>
                    <span class="text-xl">👥</span>
                </div>
            </div>
            <!-- Stat Card 2 -->
            <div class="glass-card p-6 border-emerald-500/10 flex flex-col justify-between hover:border-emerald-500/30 hover:scale-[1.02] transition-all duration-300">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Surat Cinta Dibuat</p>
                <div class="flex items-baseline justify-between mt-4">
                    <span class="text-3xl md:text-4xl font-black text-pink-400 font-mono">{{ $stats['total_surat'] }}</span>
                    <span class="text-xl">💌</span>
                </div>
            </div>
            <!-- Stat Card 3 -->
            <div class="glass-card p-6 border-emerald-500/10 flex flex-col justify-between hover:border-emerald-500/30 hover:scale-[1.02] transition-all duration-300">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Surat Dibuka</p>
                <div class="flex items-baseline justify-between mt-4">
                    <span class="text-3xl md:text-4xl font-black text-cyan-400 font-mono">{{ $stats['total_dibuka'] }}</span>
                    <span class="text-xl">🔓</span>
                </div>
            </div>
            <!-- Stat Card 4 -->
            <div class="glass-card p-6 border-emerald-500/10 flex flex-col justify-between hover:border-emerald-500/30 hover:scale-[1.02] transition-all duration-300">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Rasio Surat Dibuka</p>
                <div class="flex items-baseline justify-between mt-4">
                    <span class="text-3xl md:text-4xl font-black text-purple-400 font-mono">
                        {{ $stats['total_surat'] > 0 ? number_format(($stats['total_dibuka'] / $stats['total_surat']) * 100, 1) : 0 }}%
                    </span>
                    <span class="text-xl">📈</span>
                </div>
            </div>
            <!-- Stat Card 5 -->
            <div class="glass-card p-6 border-emerald-500/10 flex flex-col justify-between hover:border-emerald-500/30 hover:scale-[1.02] transition-all duration-300">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Pendapatan</p>
                <div class="flex items-baseline justify-between mt-4">
                    <span class="text-2xl md:text-3xl font-black text-emerald-400 font-mono">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</span>
                    <span class="text-xl">💰</span>
                </div>
            </div>
            <!-- Stat Card 6 -->
            <div class="glass-card p-6 border-emerald-500/10 flex flex-col justify-between hover:border-emerald-500/30 hover:scale-[1.02] transition-all duration-300">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Kredit Beredar</p>
                <div class="flex items-baseline justify-between mt-4">
                    <span class="text-3xl md:text-4xl font-black text-yellow-400 font-mono">{{ $stats['total_kredit'] }}</span>
                    <span class="text-xl">💎</span>
                </div>
            </div>
        </div>

        <!-- Information Report Card -->
        <div class="glass-card p-6 border-emerald-500/10">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2"><span>📢</span> Laporan Penggunaan Sistem</h3>
            <div class="space-y-4">
                <div class="p-4 bg-slate-900/55 rounded-2xl border border-white/5">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-slate-300">Kesehatan Kredit Sistem</span>
                        <span class="text-xs text-yellow-400 font-bold">Optimal</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Total kredit beredar di kalangan pengguna non-admin sebanyak <strong class="text-yellow-400">{{ $stats['total_kredit'] }}</strong>. Pembayaran yang telah disetujui berkontribusi penuh pada pendapatan riil sebesar <strong class="text-emerald-400">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</strong> dari total <strong class="text-slate-200">{{ $stats['total_transaksi'] }}</strong> pengajuan topup kredit.
                    </p>
                </div>
                
                <div class="p-4 bg-slate-900/55 rounded-2xl border border-white/5">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-slate-300">Aktivitas Surat Cinta</span>
                        <span class="text-xs text-cyan-400 font-bold">Interaktif</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Pengguna sangat giat mengirim pesan. Dari total <strong class="text-pink-400">{{ $stats['total_surat'] }}</strong> surat yang pernah dibuat dalam database, sebanyak <strong class="text-cyan-400">{{ $stats['total_dibuka'] }}</strong> surat telah berhasil dibuka oleh pasangannya masing-masing dengan rasio baca mencapai <strong class="text-purple-400">{{ $stats['total_surat'] > 0 ? number_format(($stats['total_dibuka'] / $stats['total_surat']) * 100, 1) : 0 }}%</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB CONTENT: ACTIVE USERS -->
    <div x-show="activeTab === 'users'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="glass-card overflow-hidden border-emerald-500/10">
            <div class="p-6 border-b border-white/10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2"><span>👥</span> Pengguna Terdaftar</h3>
                    <p class="text-xs text-slate-400">Daftar lengkap pengguna terdaftar sistem BucininAja.</p>
                </div>
                <div class="px-4 py-2 rounded-xl bg-slate-900 border border-white/5 text-xs text-slate-400 font-bold">
                    Total Pengguna: <span class="text-white font-mono">{{ $users->count() }}</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-white/5 border-b border-white/10 uppercase tracking-wider text-[11px] font-bold">
                        <tr>
                            <th class="px-6 py-4">Nama & Email</th>
                            <th class="px-6 py-4">WhatsApp</th>
                            <th class="px-6 py-4">Status Akun</th>
                            <th class="px-6 py-4 text-center">Surat Dibuat</th>
                            <th class="px-6 py-4 text-center font-bold text-yellow-400">Kredit</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($users as $usr)
                            <tr class="hover:bg-white/5 transition-colors">
                                <!-- Name & Email -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center font-bold text-white text-xs uppercase font-mono">
                                            {{ substr($usr->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-extrabold text-white">{{ $usr->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $usr->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <!-- WhatsApp -->
                                <td class="px-6 py-4 font-mono text-xs">
                                    @if($usr->no_wa)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $usr->no_wa) }}" target="_blank" class="text-slate-300 hover:text-emerald-400 flex items-center gap-1 transition-colors">
                                            <span>📞</span> {{ $usr->no_wa }} ↗
                                        </a>
                                    @else
                                        <span class="text-slate-600 italic">Belum diisi</span>
                                    @endif
                                </td>
                                <!-- Admin badge -->
                                <td class="px-6 py-4">
                                    @if($usr->is_admin)
                                        <span class="px-2 py-0.5 rounded bg-emerald-500/10 border border-emerald-500/30 text-[10px] font-extrabold text-emerald-400 uppercase tracking-widest">Admin</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-pink-500/10 border border-pink-500/30 text-[10px] font-extrabold text-pink-400 uppercase tracking-widest">Regular User</span>
                                    @endif
                                </td>
                                <!-- Letters Count -->
                                <td class="px-6 py-4 text-center font-bold text-white font-mono">
                                    @if($usr->is_admin)
                                        <span class="text-slate-600">-</span>
                                    @else
                                        {{ $usr->surats_count }}
                                    @endif
                                </td>
                                <!-- Credits -->
                                <td class="px-6 py-4 text-center font-mono font-bold text-yellow-400">
                                    @if($usr->is_admin)
                                        <span class="text-slate-600">UNLIMITED</span>
                                    @else
                                        {{ $usr->credits }} 💎
                                    @endif
                                </td>
                                <!-- Action -->
                                <td class="px-6 py-4">
                                    @if(!$usr->is_admin)
                                        <button @click="openModal({{ $usr->id }}, '{{ addslashes($usr->name) }}', {{ $usr->credits }}, '{{ route('admin.user.credits', $usr->id) }}')" 
                                                class="px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 hover:bg-emerald-500/25 hover:border-emerald-500/40 text-emerald-400 text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                                            <span>🔑</span> Kelola Kredit
                                        </button>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sistem Utama</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB CONTENT: PENDING TRANSACTIONS -->
    <div x-show="activeTab === 'transactions'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="glass-card overflow-hidden border-emerald-500/10">
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2"><span>💰</span> Menunggu Persetujuan</h3>
                    <p class="text-xs text-slate-400">Permintaan Top-Up yang memerlukan validasi bukti pembayaran.</p>
                </div>
            </div>

            @if($pendingTransactions->isEmpty())
                <div class="p-12 text-center text-slate-400 flex flex-col items-center justify-center gap-2">
                    <span class="text-4xl">🎉</span>
                    <p class="font-bold text-white">Kerjaan Selesai!</p>
                    <p class="text-xs">Tidak ada pengajuan top-up kredit yang pending saat ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-white/5 border-b border-white/10 uppercase tracking-wider text-[11px] font-bold">
                            <tr>
                                <th class="px-6 py-4">Invoice</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4 font-bold text-yellow-400">Kredit</th>
                                <th class="px-6 py-4">Total Harga</th>
                                <th class="px-6 py-4">Tanggal Pengajuan</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($pendingTransactions as $trx)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-white">{{ $trx->kode_invoice }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-white">{{ $trx->user->name }}</p>
                                        <p class="text-xs text-slate-500 font-mono">{{ $trx->user->email }}</p>
                                    </td>
                                    <td class="px-6 py-4 font-bold font-mono text-yellow-400">+{{ $trx->jumlah_kredit }} 💎</td>
                                    <td class="px-6 py-4 font-bold text-white font-mono">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-400">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.approve', $trx->id) }}">
                                            @csrf
                                            <button type="submit" class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/40 px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                                                <span>✓</span> Approve
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

    <!-- TAB CONTENT: PACKAGES -->
    <div x-show="activeTab === 'packages'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-white flex items-center gap-2"><span>🏷️</span> Pengaturan Paket Kredit</h3>
            <p class="text-xs text-slate-400">Atur harga jual dan detail paket kredit yang ditawarkan kepada pengguna.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($packages as $paket)
                <div class="glass-card p-6 relative overflow-hidden border-emerald-500/10 hover:border-emerald-500/30 transition-all duration-300">
                    @if($paket->is_popular)
                        <div class="absolute top-0 right-0 bg-gradient-to-r from-emerald-500 to-teal-500 text-[9px] font-black text-white px-8 py-1.5 rotate-45 translate-x-[25px] translate-y-[10px] shadow-md tracking-wider">POPULER</div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.package.update', $paket->id) }}" class="space-y-4">
                        @csrf
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 mb-1.5 uppercase tracking-widest">Nama Paket</label>
                            <input type="text" name="nama_paket" value="{{ $paket->nama_paket }}"
                                   class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 text-white text-sm outline-none transition-all duration-300" required>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="group">
                                <label class="block text-[10px] font-black text-slate-400 mb-1.5 uppercase tracking-widest">Jumlah Kredit</label>
                                <input type="number" name="jumlah_kredit" value="{{ $paket->jumlah_kredit }}" min="1"
                                       class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 text-white text-sm outline-none transition-all duration-300" required>
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-black text-slate-400 mb-1.5 uppercase tracking-widest">Harga (Rp)</label>
                                <input type="number" name="harga" value="{{ $paket->harga }}" min="0"
                                       class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 text-white text-sm outline-none transition-all duration-300" required>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <input type="checkbox" name="is_popular" id="popular_{{ $paket->id }}" class="w-4 h-4 text-emerald-500 bg-slate-900 border-white/20 rounded focus:ring-emerald-500/20 focus:ring-2" {{ $paket->is_popular ? 'checked' : '' }}>
                            <label for="popular_{{ $paket->id }}" class="text-xs font-semibold text-slate-300 cursor-pointer select-none">Tandai sebagai Paket Populer</label>
                        </div>

                        <button type="submit" class="w-full mt-4 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/30 hover:border-emerald-500/50 py-3 rounded-xl font-bold transition-all text-xs tracking-wider uppercase">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ALPINE MODAL: MANAGE USER CREDITS -->
    <div x-show="modalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        <div class="glass-card w-full max-w-md p-6 md:p-8 border border-white/20 shadow-2xl relative" @click.away="modalOpen = false">
            <!-- Close Button -->
            <button @click="modalOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-white text-2xl transition-colors">&times;</button>
            
            <h3 class="text-xl font-extrabold text-white mb-2 flex items-center gap-2"><span>💎</span> Kelola Kredit User</h3>
            <p class="text-xs text-slate-400 mb-6 leading-relaxed">
                Sesuaikan jumlah kredit untuk pengguna <strong class="text-emerald-400 font-bold" x-text="modalUserName"></strong> secara manual.
            </p>
            
            <form :action="modalAction" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-300 uppercase tracking-widest mb-2">Jumlah Kredit (💎)</label>
                    <input type="number" name="credits" x-model="modalUserCredits" required min="0"
                           class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 text-white text-sm outline-none transition-all">
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="modalOpen = false"
                            class="flex-1 py-3 bg-white/5 hover:bg-white/10 text-white text-xs font-bold rounded-xl border border-white/10 transition-all uppercase tracking-wider">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/30 hover:border-emerald-500/50 text-xs font-bold rounded-xl transition-all uppercase tracking-wider">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling Overrides for Premium Aesthetic */
    body {
        background-color: #050811 !important; /* Deep Premium Slate/Black */
    }
    
    .mesh-gradient {
        background-image: 
            radial-gradient(at 0% 0%, hsla(160, 100%, 10%, 0.4) 0, transparent 60%), 
            radial-gradient(at 50% 0%, hsla(180, 100%, 8%, 0.4) 0, transparent 60%), 
            radial-gradient(at 100% 0%, hsla(140, 100%, 10%, 0.4) 0, transparent 60%), 
            radial-gradient(at 0% 100%, hsla(200, 100%, 8%, 0.4) 0, transparent 60%) !important;
    }

    .glass-card {
        background: rgba(10, 18, 36, 0.45) !important;
        border: 1px solid rgba(16, 185, 129, 0.15) !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
    }

    .glass-nav {
        border: 1px solid rgba(16, 185, 129, 0.15) !important;
        background: rgba(10, 18, 36, 0.8) !important;
    }

    .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-bounce-subtle { animation: bounceSubtle 2s infinite; }
    @keyframes bounceSubtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }

    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

@once
@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endonce
@endsection
