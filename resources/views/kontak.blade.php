@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="text-center mb-10">
        <div class="inline-block p-4 rounded-full bg-purple-500/10 border border-purple-500/20 mb-6 shadow-[0_0_30px_rgba(168,85,247,0.2)]">
            <span class="text-4xl filter drop-shadow-lg">📬</span>
        </div>
        <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">Kirim Pesan</h2>
        <p class="text-slate-400 text-lg">Kami menerima kritik dan saran terhadap website ini.</p>
    </div>

    <form id="whatsappForm" onsubmit="return sendToWhatsApp()" class="glass-card p-8 md:p-12 space-y-6">
        
        <div class="group">
            <label class="block text-sm font-bold text-slate-300 mb-2 group-focus-within:text-pink-400 transition-colors uppercase tracking-widest">Nama Kamu</label>
            <input type="text" id="nama" class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none" placeholder="Masukkan nama..." required>
        </div>

        <div class="group">
            <label class="block text-sm font-bold text-slate-300 mb-2 group-focus-within:text-pink-400 transition-colors uppercase tracking-widest">Pesan</label>
            <textarea id="pesan" rows="5" class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 focus:bg-white/10 text-white placeholder-slate-500 transition-all duration-300 outline-none resize-y" placeholder="Sampaikan kritik atau saranmu..." required></textarea>
        </div>

        <button type="submit"
            class="w-full btn-immersive text-white font-black text-lg py-5 px-6 rounded-2xl mt-8 flex items-center justify-center gap-2 shadow-xl">
            <span>💬</span> Kirim via WhatsApp
        </button>
    </form>
</div>

<script>
    function sendToWhatsApp() {
        const nama = document.getElementById("nama").value;
        const pesan = document.getElementById("pesan").value;
        const nomor = "6285155238654"; // Ubah ke nomor WA kamu

        const text = `Halo, saya ${nama}%0A%0A${encodeURIComponent(pesan)}`;
        const link = `https://wa.me/${nomor}?text=${text}`;

        window.open(link, '_blank');
        return false; // Supaya form tidak submit ke server
    }
</script>
@endsection
