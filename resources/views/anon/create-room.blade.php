<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Room Anonim</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/833/833472.png" type="image/png">
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-100 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-lg bg-white p-8 rounded-xl shadow-md space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-pink-600">Buat Room Anonim 💌</h1>
            <p class="text-gray-500 mt-2">Dapatkan link unik yang bisa kamu bagikan untuk menerima pesan secara anonim.</p>
        </div>

        <form action="{{ route('anon.store') }}" method="POST" class="space-y-4">
            @csrf
            <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 rounded-lg text-lg">
                Buat Room Sekarang
            </button>
        </form>

        <div class="text-sm text-gray-500 text-center">
            Tidak perlu login. Langsung klik dan bagikan link-nya!
        </div>

        <div class="text-center">
            <a href="/" class="text-pink-500 hover:underline text-sm">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>
