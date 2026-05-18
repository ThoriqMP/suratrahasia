<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cinta Digital</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/833/833472.png" type="image/png">
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @stack('scripts')
    @vite('resources/css/app.css')

    <style>
        html, body {
            font-family: 'Outfit', sans-serif;
            background-color: #020617;
            color: #e2e8f0;
            overflow-x: hidden;
        }

        .mesh-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.3;
            background-image: 
                radial-gradient(at 0% 0%, hsla(339,49%,30%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(253,16%,15%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(321,50%,20%,1) 0, transparent 50%), 
                radial-gradient(at 0% 100%, hsla(280,30%,10%,1) 0, transparent 50%);
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 32px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        
        .btn-immersive {
            background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%);
            transition: all 0.3s ease;
        }
        
        .btn-immersive:hover {
            box-shadow: 0 0 20px rgba(244, 114, 182, 0.4);
            transform: translateY(-2px);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased selection:bg-pink-500/30">
    <!-- Immersive Background -->
    <div class="mesh-gradient"></div>

    <div class="min-h-screen flex flex-col relative z-10">
        <!-- Header -->
        <header class="py-6 px-4 sm:px-6 lg:px-8">
            <nav class="max-w-5xl mx-auto glass-nav px-6 py-4 flex items-center justify-between shadow-2xl transition-all">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-full bg-pink-500/20 flex items-center justify-center group-hover:bg-pink-500/30 transition-colors border border-pink-500/30">
                        <span class="text-xl">💌</span>
                    </div>
                    <h1 class="text-xl font-extrabold text-white tracking-tight italic">BucininAja</h1>
                </a>
                <div class="hidden sm:flex space-x-6 items-center">
                    <a href="/" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors">Beranda</a>
                    <a href="/tentang" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors">Tentang</a>
                    <a href="/kontak" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors">Kontak</a>
                    <a href="/create" class="px-5 py-2 text-sm font-bold text-white btn-immersive rounded-xl">Buat Surat</a>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col justify-center py-10">
            <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="glass-card p-8 md:p-12 lg:p-14 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-pink-500/10 blur-[80px] -mr-32 -mt-32 rounded-full pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/10 blur-[80px] -ml-32 -mb-32 rounded-full pointer-events-none"></div>
                    <div class="relative z-10">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-white/10 bg-slate-950/50 backdrop-blur-md">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center text-sm font-medium text-slate-500">
                    <p>&copy; 2024 BucininAja. All rights reserved.</p>
                    <p class="mt-2 flex items-center justify-center gap-1">
                        Dibuat dengan <span class="text-pink-500 animate-pulse">❤️</span> di Indonesia
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>