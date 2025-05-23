@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-12 px-6 text-center">
    <h2 class="text-3xl font-bold text-pink-600 mb-4">📬 Kirim Pesan ke WhatsApp</h2>
    <p class="text-gray-600 mb-6">Kami menerima kritik dan saran terhadap website ini.</p>

    <form id="whatsappForm" onsubmit="return sendToWhatsApp()" class="bg-pink-50 border border-pink-200 rounded-xl p-6 shadow-sm">
        <div class="mb-4 text-left">
            <label class="block text-sm text-pink-700 font-semibold mb-2">Nama Kamu</label>
            <input type="text" id="nama" class="w-full px-4 py-3 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-300" required>
        </div>

        <div class="mb-4 text-left">
            <label class="block text-sm text-pink-700 font-semibold mb-2">Pesan</label>
            <textarea id="pesan" rows="5" class="w-full px-4 py-3 border border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-300 resize-y" required></textarea>
        </div>

        <button type="submit"
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl transition-all duration-200 shadow-md w-full">
            Kirim ke WhatsApp
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
