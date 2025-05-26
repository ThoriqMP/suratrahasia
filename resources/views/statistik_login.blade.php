<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Statistik</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-pink-100 to-purple-200 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 animate-fade-in">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-pink-600">🔐 Login Statistik</h2>
            <p class="text-gray-500 mt-1 text-sm">Masukkan password untuk melihat data statistik surat cinta</p>
        </div>

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('statistik.show') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                       class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-400"
                       placeholder="••••••••" required>
            </div>

            <button type="submit"
                    class="w-full bg-pink-600 text-white py-2 rounded-md hover:bg-pink-700 transition font-semibold">
                🔎 Lihat Statistik
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('index') }}" class="text-blue-600 hover:underline text-sm">⬅ Kembali ke beranda</a>
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
