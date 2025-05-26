<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Pesan Anonim</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/833/833472.png" type="image/png">
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white max-w-lg w-full p-6 rounded-xl shadow-lg space-y-6">
        <!-- Judul -->
        <div class="text-center">
            <h1 class="text-2xl font-bold text-pink-600">✉️ Kirim Pesan Anonim</h1>
            <p class="text-gray-500 text-sm">Pesanmu akan dikirim secara anonim ke pemilik room ini.</p>
        </div>

        <!-- Form Kirim Pesan -->
        <form action="{{ route('anon.message.store', $room->kode_form) }}" method="POST" class="space-y-4">
            @csrf
            <textarea name="isi" rows="4" placeholder="Tulis pesanmu di sini..." class="w-full p-4 border border-gray-300 rounded-lg focus:ring-pink-500 focus:outline-none" required>{{ old('isi') }}</textarea>
            @error('isi')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror

            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-2 rounded-lg w-full">
                Kirim Pesan Anonim
            </button>
        </form>

        <!-- Notifikasi sukses -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif
    </div>

</body>
</html>
