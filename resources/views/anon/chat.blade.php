@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div x-data="anonChat()" x-init="init()" class="max-w-2xl mx-auto py-2 animate-fade-in-up" x-cloak>
    
    <!-- ================== STATE 1: CHOOSE GENDER ================== -->
    <div x-show="state === 'choose_gender'" class="space-y-8 text-center py-4">
        <div class="text-center mb-8">
            <div class="inline-block p-4 rounded-full bg-pink-500/15 border border-pink-500/30 mb-4 animate-pulse">
                <span class="text-4xl">💬</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">
                Pesan Anonim Match Chat
            </h2>
            <p class="text-base font-medium text-pink-400">Temukan partner obrolan anonim berdasarkan gender secara langsung!</p>
        </div>

        <div class="glass-card p-8 border-pink-500/10 space-y-6">
            <p class="text-sm font-bold text-slate-300 uppercase tracking-widest">Langkah 1: Pilih Gendermu 👤</p>
            
            <div class="grid grid-cols-2 gap-6">
                <!-- Option Male -->
                <button type="button" @click="selectGender('Laki-laki')" 
                        class="p-6 rounded-3xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-3 relative overflow-hidden group"
                        :class="gender === 'Laki-laki' ? 'border-cyan-500 bg-cyan-500/10 shadow-[0_0_25px_rgba(6,182,212,0.15)]' : 'border-white/10 hover:border-cyan-500/50 bg-slate-900/40'">
                    <span class="text-5xl group-hover:scale-110 transition-transform">👦</span>
                    <span class="text-white font-black tracking-wider uppercase text-xs">Laki-laki</span>
                    <span class="text-[10px] text-slate-400 font-medium">Bakal dipertemukan Perempuan</span>
                    <div x-show="gender === 'Laki-laki'" class="absolute top-3 right-3 text-cyan-400 font-bold text-sm">✓</div>
                </button>

                <!-- Option Female -->
                <button type="button" @click="selectGender('Perempuan')" 
                        class="p-6 rounded-3xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-3 relative overflow-hidden group"
                        :class="gender === 'Perempuan' ? 'border-pink-500 bg-pink-500/10 shadow-[0_0_25px_rgba(236,72,153,0.15)]' : 'border-white/10 hover:border-pink-500/50 bg-slate-900/40'">
                    <span class="text-5xl group-hover:scale-110 transition-transform">👧</span>
                    <span class="text-white font-black tracking-wider uppercase text-xs">Perempuan</span>
                    <span class="text-[10px] text-slate-400 font-medium">Bakal dipertemukan Laki-laki</span>
                    <div x-show="gender === 'Perempuan'" class="absolute top-3 right-3 text-pink-400 font-bold text-sm">✓</div>
                </button>
            </div>

            <!-- Error message if not selected -->
            <div x-show="error" class="p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-xs font-bold" x-transition>
                ⚠️ <span x-text="error"></span>
            </div>

            <!-- Start Match Button -->
            <button type="button" @click="startSearch()" :disabled="isLoading"
                    class="w-full py-4.5 bg-gradient-to-r from-orange-500 via-pink-500 to-rose-500 hover:from-orange-600 hover:via-pink-600 hover:to-rose-600 disabled:opacity-50 text-white font-black text-base uppercase tracking-wider rounded-2xl shadow-xl shadow-pink-500/20 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                <span x-show="isLoading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                <span x-show="!isLoading">🚀 Cari Obrolan Sekarang</span>
            </button>
        </div>

        <p class="text-xs text-slate-500 italic">
            *100% Anonim. Privasi terjaga penuh. Log obrolan dibersihkan secara instan setelah selesai.
        </p>
    </div>

    <!-- ================== STATE 2: RADAR QUEUE (MATCHING SYSTEM) ================== -->
    <div x-show="state === 'queue'" class="space-y-8 text-center py-12">
        
        <div class="relative w-48 h-48 mx-auto flex items-center justify-center mb-6">
            <div class="absolute inset-0 rounded-full bg-pink-500/10 border border-pink-500/30 animate-ping opacity-75"></div>
            <div class="absolute w-36 h-36 rounded-full bg-purple-500/20 animate-pulse opacity-50"></div>
            <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-[#ff3f6c] to-[#ff6b3f] flex items-center justify-center text-4xl shadow-xl shadow-pink-500/25 z-10">
                🔍
            </div>
        </div>

        <div class="space-y-3">
            <h2 class="text-2xl font-black text-white uppercase tracking-wider">Menghubungkan Radar...</h2>
            <p class="text-slate-400 text-sm max-w-sm mx-auto leading-relaxed">
                Sedang mencocokkan profil gendermu (<span class="font-bold text-pink-400" x-text="gender"></span>) dengan lawan jenis yang sedang online...
            </p>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 border border-white/5 rounded-full text-xs font-mono font-bold text-slate-500">
            <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
            <span>Sedang menunggu partner...</span>
        </div>

        <div class="pt-6">
            <button type="button" @click="cancelSearch()" 
                    class="py-3 px-8 bg-white/5 border border-white/10 hover:bg-rose-500/10 hover:border-rose-500/30 text-slate-400 hover:text-rose-400 font-bold rounded-2xl text-xs uppercase tracking-wider transition-all">
                ❌ Batal Mencari
            </button>
        </div>
    </div>

    <!-- ================== STATE 3: LIVE CHAT ROOM ================== -->
    <div x-show="state === 'chat'" class="glass-card flex flex-col h-[70vh] max-h-[600px] border-pink-500/20 shadow-2xl relative overflow-hidden">
        
        <!-- Header obrolan -->
        <div class="px-6 py-4 bg-slate-900/60 border-b border-white/10 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#ff3f6c]/20 to-[#ff6b3f]/20 border border-pink-500/30 flex items-center justify-center text-lg">
                    🤫
                </div>
                <div>
                    <h4 class="text-sm font-black text-white uppercase tracking-wider">Chat Anonim</h4>
                    <p class="text-[10px] flex items-center gap-1.5 mt-0.5">
                        <span class="w-2 h-2 rounded-full" :class="partnerStatus === 'active' ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400'"></span>
                        <span :class="partnerStatus === 'active' ? 'text-emerald-400 font-bold' : 'text-rose-400 font-bold'" 
                              x-text="partnerStatus === 'active' ? 'Partner Aktif' : 'Partner Meninggalkan Chat'"></span>
                    </p>
                </div>
            </div>
            
            <button type="button" @click="leaveRoom()" 
                    class="px-4 py-2 bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 border border-rose-500/30 text-[10px] font-black uppercase tracking-wider rounded-xl transition-all">
                🚪 Keluar
            </button>
        </div>

        <!-- Area Pesan (Scrollable) -->
        <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-950/20 scroll-smooth">
            
            <div class="text-center py-6 text-slate-500 text-xs font-semibold leading-relaxed max-w-sm mx-auto space-y-2">
                <p>🔒 Partner Ditemukan! Mulailah percakapan secara 100% anonim dan rahasia.</p>
                <p class="text-[10px] text-slate-600">Tip: Bersikaplah sopan dan romantis!</p>
            </div>

            <template x-for="msg in messages" :key="msg.id">
                <div class="flex" :class="msg.is_mine ? 'justify-end' : 'justify-start'">
                    <div class="max-w-[75%] rounded-[24px] px-5 py-3 shadow-md flex flex-col"
                         :class="msg.is_mine ? 
                                 'bg-gradient-to-tr from-purple-600 to-pink-600 text-white rounded-tr-none' : 
                                 'bg-white/10 text-slate-200 border border-white/5 rounded-tl-none'">
                        
                        <p class="text-sm leading-relaxed whitespace-pre-wrap break-all" x-text="msg.message"></p>
                        
                        <span class="text-[9px] text-white/50 text-right mt-1.5 font-mono" x-text="msg.time"></span>
                    </div>
                </div>
            </template>
            
            <!-- Typing Animation bubble -->
            <div x-show="isTyping" class="flex justify-start" x-transition>
                <div class="typing-bubble">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
            
            <!-- Partner meninggalkan obrolan placeholder -->
            <div x-show="partnerStatus === 'ended'" class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl text-center text-xs font-bold space-y-3">
                <p>🚪 Obrolan ini telah berakhir karena lawan bicara meninggalkan ruangan chat.</p>
                <button type="button" @click="state = 'choose_gender'" class="px-4 py-2 bg-rose-500/20 hover:bg-rose-500/30 border border-rose-500/40 text-rose-300 font-extrabold uppercase rounded-lg text-[10px] tracking-wider transition-all">
                    Cari Obrolan Baru
                </button>
            </div>

        </div>

        <!-- Input Box / Form kirim pesan -->
        <div class="p-4 bg-slate-900/60 border-t border-white/10 shrink-0">
            <form @submit.prevent="sendMessage()" class="flex gap-3">
                <input type="text" x-model="newMessage" :disabled="partnerStatus === 'ended'"
                       placeholder="Ketik pesan rahasia di sini..." 
                       class="flex-1 px-5 py-3.5 bg-slate-950/60 border border-white/10 rounded-2xl focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 text-white placeholder-slate-500 text-sm outline-none transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                
                <button type="submit" :disabled="!newMessage.trim() || partnerStatus === 'ended'"
                        class="px-6 py-3.5 bg-gradient-to-r from-orange-500 via-pink-500 to-rose-500 hover:from-orange-600 hover:via-pink-600 hover:to-rose-600 disabled:opacity-50 text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-pink-500/15 transition-all flex items-center justify-center gap-1.5 shrink-0">
                    <span>🚀</span> Kirim
                </button>
            </form>
        </div>

    </div>

</div>

<script>
function anonChat() {
    return {
        state: 'choose_gender', // choose_gender, queue, chat
        gender: '',
        roomToken: '{{ $activeRoomToken }}',
        messages: [],
        newMessage: '',
        queueInterval: null,
        chatInterval: null,
        partnerStatus: 'active', // active, ended
        error: '',
        isLoading: false,
        isTyping: false,
        shownMessageIds: [],
        typingQueue: [],
        isTypingInProgress: false,

        init() {
            if (this.roomToken) {
                this.enterRoom(this.roomToken);
            }
        },

        selectGender(g) {
            this.gender = g;
        },

        async startSearch() {
            if (!this.gender) {
                this.error = 'Silakan pilih gender terlebih dahulu!';
                return;
            }
            this.error = '';
            this.isLoading = true;

            try {
                let response = await fetch('/anon-chat/join', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ gender: this.gender })
                });
                let data = await response.json();
                
                if (data.status === 'matched') {
                    this.enterRoom(data.room_token);
                } else {
                    this.state = 'queue';
                    this.startQueuePolling();
                }
            } catch (err) {
                this.error = 'Terjadi kesalahan sistem, silakan coba lagi.';
                console.error(err);
            } finally {
                this.isLoading = false;
            }
        },

        startQueuePolling() {
            this.queueInterval = setInterval(async () => {
                try {
                    let response = await fetch('/anon-chat/status');
                    let data = await response.json();
                    
                    if (data.status === 'matched') {
                        clearInterval(this.queueInterval);
                        this.enterRoom(data.room_token);
                    }
                } catch (err) {
                    console.error('Polling error:', err);
                }
            }, 2000);
        },

        async cancelSearch() {
            clearInterval(this.queueInterval);
            try {
                await fetch('/anon-chat/leave-queue', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
            } catch (err) {
                console.error(err);
            }
            this.state = 'choose_gender';
        },

        enterRoom(token) {
            this.roomToken = token;
            this.state = 'chat';
            this.messages = [];
            this.newMessage = '';
            this.partnerStatus = 'active';
            this.shownMessageIds = [];
            this.typingQueue = [];
            this.isTyping = false;
            this.isTypingInProgress = false;
            
            // Poll for messages immediately and then periodically
            this.pollMessages();
            this.chatInterval = setInterval(() => this.pollMessages(), 2000);
        },

        async pollMessages() {
            if (!this.roomToken || this.state !== 'chat') return;

            try {
                let response = await fetch(`/anon-chat/messages?room_token=${this.roomToken}`);
                let data = await response.json();
                
                this.partnerStatus = data.partner_status;
                
                // Process messages list
                const serverMessages = data.messages;
                serverMessages.forEach(msg => {
                    if (msg.is_mine || this.shownMessageIds.includes(msg.id)) {
                        if (!this.messages.some(m => m.id === msg.id)) {
                            this.messages.push(msg);
                            if (!this.shownMessageIds.includes(msg.id)) {
                                this.shownMessageIds.push(msg.id);
                            }
                            this.scrollToBottom();
                        }
                    } else {
                        // Partner/Bot message that has not been shown/typed yet!
                        if (!this.messages.some(m => m.id === msg.id) && !this.typingQueue.some(m => m.id === msg.id)) {
                            this.typingQueue.push(msg);
                            this.processTypingQueue();
                        }
                    }
                });

                if (data.room_status === 'ended') {
                    clearInterval(this.chatInterval);
                }
            } catch (err) {
                console.error('Poll messages error:', err);
            }
        },

        async processTypingQueue() {
            if (this.isTypingInProgress || this.typingQueue.length === 0) return;

            this.isTypingInProgress = true;
            const nextMsg = this.typingQueue.shift();

            // Show typing animation bubble
            this.isTyping = true;
            this.scrollToBottom();

            // Calculate typing duration based on message length: 40ms per character, min 1000ms, max 3000ms
            const delay = Math.min(3000, Math.max(1000, nextMsg.message.length * 40));

            setTimeout(() => {
                this.isTyping = false;
                this.messages.push(nextMsg);
                this.shownMessageIds.push(nextMsg.id);
                this.scrollToBottom();

                this.isTypingInProgress = false;
                // Continue queue processing
                this.processTypingQueue();
            }, delay);
        },

        async sendMessage() {
            if (!this.newMessage.trim() || this.partnerStatus === 'ended') return;

            const textToSend = this.newMessage;
            this.newMessage = '';

            try {
                let response = await fetch('/anon-chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        room_token: this.roomToken,
                        message: textToSend
                    })
                });
                let data = await response.json();
                
                if (data.status === 'success') {
                    this.messages.push(data.message);
                    this.shownMessageIds.push(data.message.id);
                    this.scrollToBottom();
                }
            } catch (err) {
                console.error('Send error:', err);
            }
        },

        async leaveRoom() {
            if (confirm('Apakah kamu yakin ingin mengakhiri obrolan anonim ini?')) {
                clearInterval(this.chatInterval);
                try {
                    await fetch('/anon-chat/leave', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ room_token: this.roomToken })
                    });
                } catch (err) {
                    console.error(err);
                }
                this.state = 'choose_gender';
                this.roomToken = '';
                this.messages = [];
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.chatContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        }
    };
}
</script>

<style>
    /* Styling Overrides */
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
        animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    [x-cloak] {
        display: none !important;
    }

    /* Typing Animation CSS */
    .typing-bubble {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 12px 18px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 20px 20px 20px 4px;
        width: fit-content;
        align-self: flex-start;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 4px;
        margin-bottom: 4px;
    }
    .typing-dot {
        width: 6px;
        height: 6px;
        background: #f472b6;
        border-radius: 50%;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
        40% { transform: scale(1.2); opacity: 1; }
    }
</style>

@once
@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endonce

@endsection
