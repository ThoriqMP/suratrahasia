<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Statistik Surat Cinta</title>
    <link rel="icon" href="" class="fa fa-heart" />
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-pink-100 to-purple-200 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-xl bg-white shadow-lg rounded-2xl p-8 space-y-6 animate-fade-in">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-pink-600 mb-2">📊 Statistik Surat Cinta</h2>
            <p class="text-gray-500">Melihat jumlah surat yang dibuat dan dibuka</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-green-100 border border-green-200 rounded-xl p-6 text-center shadow-sm hover:scale-105 transition">
                <p class="text-lg text-green-700 font-medium mb-2">Total Surat Dibuat</p>
                <p class="text-4xl font-bold text-green-900">{{ $jumlahSurat }}</p>
            </div>

            <div class="bg-yellow-100 border border-yellow-200 rounded-xl p-6 text-center shadow-sm hover:scale-105 transition">
                <p class="text-lg text-yellow-700 font-medium mb-2">Total Surat Dibuka</p>
                <p class="text-4xl font-bold text-yellow-900">{{ $jumlahDibuka }}</p>
            </div>
        </div>

        <!-- Form Pencarian Surat -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">🔍 Cari Surat Berdasarkan Kode</h3>
            <form action="{{ route('surat.search') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-4">
                @csrf
                <input
                    type="text"
                    name="kode"
                    maxlength="8"
                    placeholder="Masukkan kode surat..."
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 transition"
                    required
                >
                <button
                    type="submit"
                    class="px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition font-semibold w-full sm:w-auto"
                >
                    Cari Surat
                </button>
            </form>

            @if(session('error'))
                <p class="mt-3 text-center text-red-500 font-medium">{{ session('error') }}</p>
            @endif
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('statistik.form') }}"
               class="inline-block bg-pink-600 text-white px-6 py-2 rounded-full hover:bg-pink-700 transition">
                🔙 Kembali
            </a>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
        }
    </style>
</body>
</html>
