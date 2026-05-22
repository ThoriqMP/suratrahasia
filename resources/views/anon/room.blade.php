@extends('layouts.app')

@section('content')
<div x-data="{ 
    modalOpen: false,
    messageText: '',
    messageDate: '',
    deleteUrl: '',
    copiedSend: false,
    copiedRoom: false,
    openMessage(text, date, url) {
        this.messageText = text;
        this.messageDate = date;
        this.deleteUrl = url;
        this.modalOpen = true;
    },
    copyToClipboard(elementId, type) {
        const linkEl = document.getElementById(elementId);
        if (!linkEl) return;
        const text = linkEl.innerText.trim();
        navigator.clipboard.writeText(text).then(() => {
            if (type === 'send') {
                this.copiedSend = true;
                setTimeout(() => this.copiedSend = false, 2500);
            } else {
                this.copiedRoom = true;
                setTimeout(() => this.copiedRoom = false, 2500);
            }
        });
    }
}" class="max-w-4xl mx-auto py-4 animate-fade-in-up">

    <!-- Header / Branding Section -->
    <div class="glass-card p-6 md:p-8 mb-8 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden border-pink-500/20 shadow-[0_0_50px_rgba(236,72,153,0.05)]">
        <div class="absolute inset-0 bg-gradient-to-r from-pink-500/5 to-transparent pointer-events-none"></div>
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#ff3f6c]/20 to-[#ff6b3f]/20 border border-pink-500/30 flex items-center justify-center text-3xl shadow-lg shadow-pink-500/10">
                📨
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight">Kotak Bisik Rahasia</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-pink-500/10 border border-pink-500/30 text-[10px] font-bold text-pink-400 uppercase tracking-widest">Inbox</span>
                </div>
                <p class="text-slate-400 text-sm mt-0.5">Kelola amplop pesan rahasia dan bagikan link-mu ke sosial media.</p>
            </div>
        </div>
        <div class="bg-slate-900/40 px-5 py-3 rounded-2xl border border-white/5 text-center relative z-10 shrink-0">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total Bisikan</p>
            <p class="text-3xl font-black text-pink-400 font-mono mt-0.5">{{ $messages->count() }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-bold flex items-center gap-2 animate-bounce-subtle">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <!-- Sharing Cockpit -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Submission Link -->
        <div class="glass-card p-6 border-pink-500/10 relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-black text-pink-400 uppercase tracking-wider">🔗 Link Kirim Pesan (Sosmed)</span>
                    <span x-show="copiedSend" class="text-[10px] bg-pink-500/10 text-pink-400 border border-pink-500/20 px-2 py-0.5 rounded font-bold animate-pulse" x-cloak>Tersalin!</span>
                </div>
                <p class="text-xs text-slate-400 mb-3">Bagikan link ini ke Instagram Stories, Bio, atau Status WhatsApp-mu.</p>
                <code id="linkSend" class="block p-3 rounded-xl bg-slate-950/60 border border-white/5 font-mono text-xs text-pink-300 break-all select-all font-bold">
                    {{ route('anon.send.form', $room->kode_form) }}
                </code>
            </div>
            <button @click="copyToClipboard('linkSend', 'send')" class="w-full mt-4 py-3 bg-gradient-to-r from-orange-500 via-pink-500 to-rose-500 hover:from-orange-600 hover:via-pink-600 hover:to-rose-600 text-white font-extrabold rounded-xl text-xs uppercase tracking-wider transition-all transform hover:-translate-y-0.5 shadow-lg shadow-pink-500/15">
                📋 Salin Link Curhatan
            </button>
        </div>

        <!-- Admin Room Code Link -->
        <div class="glass-card p-6 border-purple-500/10 relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-black text-purple-400 uppercase tracking-wider">🔒 Link Kelola Inbox (Rahasia)</span>
                    <span x-show="copiedRoom" class="text-[10px] bg-purple-500/10 text-purple-400 border border-purple-500/20 px-2 py-0.5 rounded font-bold animate-pulse" x-cloak>Tersalin!</span>
                </div>
                <p class="text-xs text-slate-400 mb-3">SIMPAN LINK INI! Digunakan untuk kembali membuka dan membaca pesan masuk.</p>
                <code id="linkRoom" class="block p-3 rounded-xl bg-slate-950/60 border border-white/5 font-mono text-xs text-purple-300 break-all select-all font-bold">
                    {{ route('anon.show', $room->kode) }}
                </code>
            </div>
            <button @click="copyToClipboard('linkRoom', 'room')" class="w-full mt-4 py-3 bg-purple-500/20 hover:bg-purple-500/30 text-purple-400 border border-purple-500/30 hover:border-purple-500/40 font-extrabold rounded-xl text-xs uppercase tracking-wider transition-all">
                💾 Salin Link Owner
            </button>
        </div>
    </div>

    <!-- Messages List / Envelope Grid -->
    <div class="glass-card p-6 md:p-8 border-pink-500/10">
        <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
            <span>📫</span> Bisikan Masuk
        </h3>

        @if($messages->isEmpty())
            <div class="p-12 text-center text-slate-400 flex flex-col items-center justify-center gap-3">
                <span class="text-5xl animate-bounce-subtle">📭</span>
                <p class="font-bold text-white text-base">Belum Ada Bisikan Masuk</p>
                <p class="text-xs max-w-sm mx-auto leading-relaxed">
                    Ayo bagikan link kirim pesan kamu ke bio Instagram, Story, atau grup WhatsApp untuk mengumpulkan curhatan rahasia dari teman-temanmu!
                </p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($messages as $index => $message)
                    <div @click="openMessage('{{ addslashes($message->isi) }}', '{{ $message->created_at->format('d M Y, H:i') }}', '{{ route('anon.message.delete', $message->id) }}')" 
                         class="group relative aspect-square rounded-2xl bg-gradient-to-br from-[#ff3f6c]/10 to-[#ff6b3f]/10 border border-[#ff3f6c]/20 hover:border-[#ff3f6c]/50 hover:from-[#ff3f6c]/20 hover:to-[#ff6b3f]/20 shadow-md cursor-pointer transition-all duration-300 flex flex-col items-center justify-center gap-2 text-center select-none overflow-hidden active:scale-95">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                        
                        <!-- Pulse Ring for new messages -->
                        <span class="w-12 h-12 rounded-full bg-pink-500/10 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                            ✉️
                        </span>
                        
                        <div>
                            <p class="text-white font-black text-xs uppercase tracking-wider">Bisik Rahasia</p>
                            <p class="text-[9px] text-slate-500 font-mono mt-0.5">{{ $message->created_at->diffForHumans() }}</p>
                        </div>

                        <!-- Read Cover Overlay -->
                        <div class="absolute bottom-3 text-[9px] font-black text-[#ff3f6c] uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                            Buka 🤫
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ALPINE MODAL: VIEW & SHARE MESSAGE CARD -->
    <div x-show="modalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        <div class="glass-card w-full max-w-md p-6 border border-white/20 shadow-2xl relative" @click.away="modalOpen = false">
            <!-- Close button -->
            <button @click="modalOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-white text-2xl transition-colors font-bold">&times;</button>
            
            <h3 class="text-base font-extrabold text-white mb-6 flex items-center gap-2"><span>💬</span> Detail Bisik Rahasia</h3>
            
            <!-- Instagram Story Card Mockup Container (Red gradient) -->
            <div id="shareCardContainer" class="rounded-[24px] bg-gradient-to-b from-[#ff3f6c] to-[#ff6b3f] p-5 shadow-xl text-center relative overflow-hidden select-none mb-6">
                <!-- Sparks decoration -->
                <div class="absolute top-4 left-4 text-white/30 text-xs">✨</div>
                <div class="absolute bottom-16 right-4 text-white/20 text-xs">✨</div>
                
                <div class="w-10 h-10 rounded-full bg-white/20 border border-white/30 flex items-center justify-center text-lg mx-auto shadow-md">
                    🤫
                </div>
                <p class="text-white font-extrabold text-[10px] uppercase tracking-widest mt-1">Bisik Rahasia</p>
                
                <!-- White Message Body Card -->
                <div class="bg-white rounded-2xl p-5 shadow-lg w-full mt-4 text-center border border-pink-100/50">
                    <p class="text-slate-400 font-black text-[9px] uppercase tracking-widest mb-3">🤫 TANYA AKU APA SAJA</p>
                    <p class="text-slate-800 font-extrabold text-base leading-relaxed break-words" x-text="messageText"></p>
                    <p class="text-pink-500 font-bold text-[10px] mt-4">Balas di website BucininAja!</p>
                </div>
                
                <!-- Safety subtext -->
                <p class="text-white/80 font-bold text-[8px] tracking-widest uppercase mt-4">
                    🔒 100% Aman & Anonim
                </p>
            </div>

            <!-- Action buttons -->
            <div class="space-y-3">
                <!-- Download image triggers custom Javascript Exporter -->
                <button @click="downloadShareCard(messageText)" 
                        class="w-full py-3.5 bg-gradient-to-r from-orange-500 via-pink-500 to-rose-500 hover:from-orange-600 hover:via-pink-600 hover:to-rose-600 text-white font-extrabold rounded-xl text-xs uppercase tracking-wider transition-all transform hover:-translate-y-0.5 shadow-lg shadow-pink-500/15 flex items-center justify-center gap-2">
                    <span>📸</span> Unduh Gambar Instagram (PNG)
                </button>

                <!-- Copy Link Sticker Shortcut -->
                <button @click="copyToClipboard('linkSend', 'send')"
                        class="w-full py-3 bg-slate-900 border border-white/10 hover:border-pink-500/50 hover:bg-slate-950 text-slate-300 hover:text-white font-bold rounded-xl text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2">
                    <span>🔗</span> <span x-text="copiedSend ? 'Link Stiker Tersalin! ✅' : 'Salin Link Stiker Tautan Instagram'"></span>
                </button>

                <!-- Step-by-Step Instagram Story Sharing Guide -->
                <div class="p-4 bg-slate-950/60 rounded-2xl border border-white/5 text-left space-y-2">
                    <p class="text-[11px] font-black text-pink-400 uppercase tracking-widest flex items-center gap-1.5">
                        <span>📸</span> Cara Share ke Instagram Stories:
                    </p>
                    <ol class="text-[10px] text-slate-300 list-decimal pl-4 space-y-1 font-medium leading-relaxed">
                        <li>Klik <strong>Unduh Gambar (PNG)</strong> di atas untuk menyimpan kartu ke hp.</li>
                        <li>Klik <strong>Salin Link Stiker</strong> untuk menyalin link kirim pesan kamu.</li>
                        <li>Buka Instagram Story, buat story baru, dan pilih gambar kartu yang diunduh.</li>
                        <li>Ketuk ikon <strong>Stiker</strong> di kanan atas, lalu pilih stiker <strong>Tautan (Link)</strong>.</li>
                        <li>Tempelkan (paste) link yang telah disalin dan taruh stiker tautan tersebut tepat di atas gambar!</li>
                    </ol>
                    <p class="text-[9px] text-slate-500 italic font-medium leading-normal mt-1">
                        *Dengan stiker ini, teman/followers dapat mengetuk langsung dari Instagram Story Anda untuk mengirimkan balasan pesan rahasia!
                    </p>
                </div>
                
                <div class="flex gap-3">
                    <button @click="modalOpen = false" 
                            class="flex-1 py-3 bg-white/5 hover:bg-white/10 text-white text-xs font-bold rounded-xl border border-white/10 transition-all uppercase tracking-wider">
                        Tutup
                    </button>
                    
                    <!-- Delete Form -->
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" @click="if(!confirm('Apakah kamu yakin ingin menghapus pesan anonim ini selamanya?')) event.preventDefault();"
                                class="w-full py-3 bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 border border-rose-500/30 hover:border-rose-500/50 text-xs font-bold rounded-xl transition-all uppercase tracking-wider">
                            🗑️ Hapus Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    /**
     * Draw 1080x1920 high-resolution Instagram Story image of NGL card and trigger PNG download
     */
    function downloadShareCard(text) {
        // Create canvas of 1080x1920 (Standard Story size)
        const canvas = document.createElement('canvas');
        canvas.width = 1080;
        canvas.height = 1920;
        const ctx = canvas.getContext('2d');

        // 1. Draw NGL Orange-Red Gradient Background
        const grad = ctx.createLinearGradient(0, 0, 0, 1920);
        grad.addColorStop(0, '#ff3f6c');
        grad.addColorStop(1, '#ff6b3f');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, 1080, 1920);

        // 2. Sparkling neon shapes
        ctx.fillStyle = 'rgba(255, 255, 255, 0.15)';
        ctx.font = 'bold 42px sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('✨', 160, 240);
        ctx.fillText('✨', 920, 1640);
        ctx.fillText('❤️', 180, 1420);
        ctx.fillText('❤️', 900, 420);

        // 3. Header Branding Icon
        ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
        ctx.beginPath();
        ctx.arc(540, 310, 75, 0, Math.PI * 2);
        ctx.fill();

        ctx.fillStyle = '#ffffff';
        ctx.font = '65px sans-serif';
        ctx.fillText('💬', 540, 305);

        ctx.font = '900 32px sans-serif';
        // Note: letterSpacing works natively in modern browser context
        ctx.letterSpacing = '6px'; 
        ctx.fillText('BISIK RAHASIA', 540, 425);
        
        ctx.fillStyle = 'rgba(255, 255, 255, 0.75)';
        ctx.font = '600 24px sans-serif';
        ctx.fillText('Kirim aku pesan anonim!', 540, 470);

        // 4. Draw White Question Card (Centered)
        const cardX = 140;
        const cardY = 660;
        const cardW = 800;
        const cardH = 500;
        const radius = 45;

        // Shadow under white card
        ctx.shadowColor = 'rgba(0, 0, 0, 0.25)';
        ctx.shadowBlur = 45;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 25;
        
        ctx.fillStyle = '#ffffff';
        ctx.beginPath();
        ctx.roundRect(cardX, cardY, cardW, cardH, radius);
        ctx.fill();
        
        // Reset Shadow for next operations
        ctx.shadowBlur = 0;
        ctx.shadowOffsetY = 0;

        // Card Header label
        ctx.fillStyle = '#94a3b8'; // slate-400
        ctx.font = '900 22px sans-serif';
        ctx.fillText('🤫 TANYA AKU APA SAJA', 540, 735);

        // Card Body Text wrapping logic
        ctx.fillStyle = '#0f172a'; // slate-900
        ctx.font = 'bold 36px sans-serif';
        const words = text.split(' ');
        let line = '';
        const lines = [];
        const maxWidth = 700;
        const lineHeight = 55;

        for (let n = 0; n < words.length; n++) {
            let testLine = line + words[n] + ' ';
            let metrics = ctx.measureText(testLine);
            let testWidth = metrics.width;
            if (testWidth > maxWidth && n > 0) {
                lines.push(line);
                line = words[n] + ' ';
            } else {
                line = testLine;
            }
        }
        lines.push(line);

        // Render card lines centered inside body
        const startY = cardY + 235 - ((lines.length - 1) * lineHeight) / 2;
        for (let i = 0; i < lines.length; i++) {
            ctx.fillText(lines[i].trim(), 540, startY + i * lineHeight);
        }

        // Card footer text
        ctx.fillStyle = '#f43f5e'; // rose-500
        ctx.font = 'bold 22px sans-serif';
        ctx.fillText('Balas di website BucininAja!', 540, cardY + 440);

        // 5. Draw Bottom Safety Badge
        ctx.fillStyle = 'rgba(0, 0, 0, 0.25)';
        ctx.beginPath();
        ctx.roundRect(340, 1750, 400, 70, 35);
        ctx.fill();
        
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 22px sans-serif';
        ctx.fillText('🔒 100% AMAN & ANONIM', 540, 1785);

        // Create virtual download anchor tag
        const link = document.createElement('a');
        link.download = 'bisik-rahasia-' + Math.floor(Math.random() * 900000 + 100000) + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    }
</script>

<style>
    /* Premium Styling Overrides */
    body {
        background-color: #050811 !important;
    }
    
    .glass-card {
        background: rgba(15, 23, 42, 0.45) !important;
        border: 1px solid rgba(244, 114, 182, 0.15) !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important;
        backdrop-filter: blur(20px) !important;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-bounce-subtle {
        animation: bounceSubtle 2.5s infinite ease-in-out;
    }
    @keyframes bounceSubtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
</style>
@endsection
