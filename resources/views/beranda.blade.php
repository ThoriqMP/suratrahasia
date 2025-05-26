<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cinta</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/833/833472.png" type="image/png">
    @stack('scripts')
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-100 min-h-screen font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm relative z-50">
            <nav class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-pink-600">BucininAja</h1>
                <div class="space-x-4">
                    <a href="\" class="text-pink-600 hover:text-pink-700">Beranda</a>
                    <a href="\tentang" class="text-pink-600 hover:text-pink-700">Tentang</a>
                    <a href="\kontak" class="text-pink-600 hover:text-pink-700">Kontak</a>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="relative isolate px-6 pt-14 lg:px-8 bg-white">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-pink-300 to-purple-400 opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div class="text-center">
                    <h1 class="text-5xl font-semibold tracking-tight text-gray-900 sm:text-7xl">Surat Cinta Digital</h1>
                    <p class="mt-8 text-lg text-gray-500 sm:text-xl">Buat dan kirim surat cinta digital yang penuh makna. Karena setiap kata memiliki perasaan yang layak diabadikan.</p>
                    <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4 sm:gap-x-6">
                        <a href="/create" class="rounded-md bg-pink-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-pink-500">Tulis Surat</a>
                        <a href="/anon" class="rounded-md bg-purple-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-purple-400">Pesan Anonim</a>
                        <a href="/tentang" class="text-sm font-semibold text-pink-600 hover:text-pink-800 flex items-center gap-1">Pelajari Lebih Lanjut <span aria-hidden="true">→</span></a>
                    </div>
                </div>
            </div>
            <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-pink-300 to-purple-400 opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
        </section>
        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center text-gray-600">
                    <p>&copy; 2024 MyLoveLetters. All rights reserved.</p>
                    <p class="mt-2">Dibuat dengan ❤️ di Indonesia</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
