<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hasil Pencarian Surat</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-pink-100 to-purple-200 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-xl w-full bg-white rounded-2xl shadow-lg p-8 space-y-6 animate-fade-in">
        <h2 class="text-3xl font-bold text-pink-600 mb-4 text-center">🔍 Hasil Pencarian Surat</h2>

        @if ($surat)
            <div class="p-6 bg-green-50 rounded-xl shadow-sm">
                <p><strong>Kode Surat:</strong> {{ $surat->kode }}</p>
                <p><strong>Dari:</strong> {{ $surat->dari }}</p>
                <p><strong>Untuk:</strong> {{ $surat->untuk }}</p>
                <p><strong>Status:</strong> 
                    @if($surat->dibuka_pada)
                        <span class="text-green-700 font-semibold">Sudah Dibuka</span> sejak {{ \Carbon\Carbon::parse($surat->dibuka_pada)->format('d M Y H:i') }}
                    @else
                        <span class="text-yellow-600 font-semibold">Belum Dibuka</span>
                    @endif
                </p>
            </div>
        @else
            <p class="text-center text-red-600 font-semibold">Surat dengan kode <code>{{ $kode }}</code> tidak ditemukan.</p>
        @endif


        <div class="text-center mt-6">
            <a href="{{ route('statistik.form') }}" class="inline-block bg-pink-600 text-white px-6 py-2 rounded-full hover:bg-pink-700 transition">🔙 Kembali ke Statistik</a>
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
