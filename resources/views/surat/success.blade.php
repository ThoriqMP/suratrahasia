@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto text-center px-4 py-12 sm:py-16 lg:py-20">
    <div class="space-y-8">
        <!-- Icon & Heading -->
        <div class="animate-bounce">
            <div class="inline-flex items-center justify-center bg-pink-100 rounded-full w-20 h-20">
                🎉
            </div>
        </div>
        <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600">
            Surat Berhasil Dibuat!
        </h2>
        
        <!-- Link Section -->
        <div class="mt-8 space-y-6">
            <p class="text-lg text-gray-600">Bagikan link ini ke orang yang kamu tuju</p>
            
            <div class="bg-white p-6 rounded-xl shadow-lg border border-pink-100">
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" id="suratLink" value="{{ url('/surat/' . $kode) }}" 
                        class="flex-1 px-5 py-3 border-2 border-pink-100 rounded-xl text-gray-800 bg-gray-50 focus:outline-none focus:border-pink-300 focus:ring-2 focus:ring-pink-50 transition-all"
                        readonly>
                    <button onclick="copyLink()" 
                        class="flex-shrink-0 flex items-center justify-center gap-2 bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium">Salin</span>
                    </button>
                </div>
                <div id="copyMessage" class="mt-3 flex items-center justify-center gap-1 text-sm text-green-600 opacity-0 transition-opacity duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Link berhasil disalin!
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="mt-10">
            <a href="/" class="inline-flex items-center gap-2 text-pink-600 hover:text-pink-700 font-medium transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Tulis surat baru</span>
            </a>
        </div>
    </div>
</div>

<script>
    async function copyLink() {
        const link = document.getElementById('suratLink').value;
        const message = document.getElementById('copyMessage');
        
        try {
            await navigator.clipboard.writeText(link);
            message.classList.remove('opacity-0');
            setTimeout(() => {
                message.classList.add('opacity-0');
            }, 2000);
        } catch (err) {
            console.error('Failed to copy:', err);
        }
    }
</script>
@endsection