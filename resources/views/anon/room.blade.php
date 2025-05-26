<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Room Anonim #{{ $room->kode }}</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/833/833472.png" type="image/png" />
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-100 min-h-screen flex flex-col items-center justify-start py-10 px-4">

    <div class="w-full max-w-3xl bg-white p-8 rounded-xl shadow-md space-y-8">
        <!-- Header -->
        <div class="text-center space-y-3">
            <h1 class="text-3xl font-extrabold text-pink-600">📨 Room Anonim</h1>
            <p class="text-gray-600 text-sm max-w-md mx-auto">
                Kamu dapat menerima pesan anonim di halaman ini.<br />
                Bagikan link berikut agar orang lain dapat mengirim pesan secara anonim.
            </p>
        </div>

        <!-- Tombol toggle di atas link-link -->
        <div class="relative p-4 mb-6">
            <button
                id="toggleAllLinksBtn"
                onclick="toggleAllLinks()"
                class="absolute top-0 right-0 flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white rounded-md px-4 py-2 text-sm font-semibold transition"
                aria-label="Tampilkan atau sembunyikan semua link"
                type="button"
            >
                <!-- Icon mata (eye) dari Heroicons -->
                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span id="toggleBtnText">Hide Links</span>
            </button>
        </div>


        <!-- Link Kirim Pesan -->
        <div id="linkSendContainer" class="bg-pink-50 border border-pink-300 rounded-lg px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0 mb-4">
            <div>
                <p class="text-pink-700 font-semibold text-sm mb-1">🔗 Link untuk Mengirim Pesan</p>
                <code id="linkSend" class="block font-mono text-pink-900 text-sm break-all select-all">
                {{ url('/anon/send/' . $room->kode_form) }}
                </code>
            </div>
            <button
                onclick="saveAndCopy('linkSend', '{{ $room->kode_form }}')"
                class="bg-pink-600 hover:bg-pink-700 text-white rounded-md px-4 py-2 text-sm font-semibold transition"
                aria-label="Simpan dan Salin link kirim pesan"
            >
                💾 Simpan & Salin
            </button>
        </div>

        <!-- Link Room Pemilik -->
        <div id="linkRoomContainer" class="bg-purple-50 border border-purple-300 rounded-lg px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
        <div>
            <p class="text-purple-700 font-semibold text-sm mb-1">🔗 Link untuk Membuka Room Kamu</p>
            <code id="linkRoom" class="block font-mono text-purple-900 text-sm break-all select-all">
            {{ url('/anon/' . $room->kode) }}
            </code>
        </div>
        <button
            onclick="saveAndCopy('linkRoom', '{{ $room->kode }}')"
            class="bg-purple-600 hover:bg-purple-700 text-white rounded-md px-4 py-2 text-sm font-semibold transition"
            aria-label="Simpan dan Salin link room"
        >
            💾 Simpan & Salin
        </button>
        </div>

        <!-- Notification -->
        <div
            id="notif"
            class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded-md shadow-md text-sm font-medium opacity-0 pointer-events-none transition-opacity duration-300"
            role="alert"
            aria-live="polite"
        >
            ✅ Room disimpan & link disalin!
        </div>

        <!-- Daftar Pesan -->
        @php
            $countMessages = $messages->count();
        @endphp

        <div class="grid grid-cols-3 gap-6">
            @foreach ($messages as $index => $message)
                <div
                    @class([
                        'bg-white rounded-lg p-4 shadow transition hover:shadow-lg',
                        'col-span-3' => $countMessages == 1,
                        'col-span-2' => $countMessages == 2 && $index == 0,
                        'col-span-1' => ($countMessages == 2 && $index == 1) || $countMessages >= 3,
                    ])
                >
                    <p class="text-gray-800 whitespace-pre-line">{{ $message->isi }}</p>
                    <p class="text-xs text-gray-500 text-right mt-2">{{ $message->created_at->format('d M Y H:i') }}</p>
                </div>
            @endforeach
        </div>


    </div>

    <script>
    function saveAndCopy(elementId, roomCode) {
        const linkEl = document.getElementById(elementId);
        if (!linkEl) return;

        const link = linkEl.innerText;
        localStorage.setItem('myAnonRoom', roomCode);
        navigator.clipboard.writeText(link).then(() => {
            const notif = document.getElementById('notif');
            notif.style.opacity = '1';
            notif.classList.remove('pointer-events-none');
            setTimeout(() => {
                notif.style.opacity = '0';
                notif.classList.add('pointer-events-none');
            }, 3000);
        });
    }

    function toggleAllLinks() {
        const containers = [
            document.getElementById('linkSendContainer'),
            document.getElementById('linkRoomContainer')
        ];

        const toggleBtn = document.getElementById('toggleAllLinksBtn');
        const toggleBtnText = document.getElementById('toggleBtnText');
        const eyeIcon = document.getElementById('eyeIcon');

        if (!containers.every(c => c)) return;

        // Cek kondisi saat ini, ambil dari container pertama
        const isHidden = containers[0].style.display === 'none';

        containers.forEach(container => {
            container.style.display = isHidden ? 'flex' : 'none';
        });

        if (isHidden) {
            toggleBtnText.textContent = 'Hide Links';
            // Ganti icon jadi mata terbuka
            eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
            toggleBtn.setAttribute('aria-label', 'Sembunyikan semua link');
        } else {
            toggleBtnText.textContent = 'Show Links';
            // Ganti icon jadi mata tertutup (eye-off)
            eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.969 9.969 0 012.223-3.425m3.85-2.26A9.969 9.969 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.96 9.96 0 01-1.942 3.468M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
            `;
            toggleBtn.setAttribute('aria-label', 'Tampilkan semua link');
        }
    }

</script>

</body>
</html>
