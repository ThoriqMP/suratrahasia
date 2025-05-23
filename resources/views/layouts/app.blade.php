<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cinta</title>
    <link rel="icon" href   ="" class="fa fa-heart">
    @stack('scripts')
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-100 min-h-screen font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <nav class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-pink-600">MyLoveLetters</h1>
                    <div class="space-x-4">
                        <a href="\" class="text-pink-600 hover:text-pink-700">Beranda</a>
                        <a href="\tentang" class="text-pink-600 hover:text-pink-700">Tentang</a>
                        <a href="\kontak" class="text-pink-600 hover:text-pink-700">Kontak</a>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            <div class="max-w-4xl mx-auto px-2 sm:px-4 lg:px-2 py-4">
                <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 lg:p-16">
                    @yield('content')
                </div>
            </div>
        </main>

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