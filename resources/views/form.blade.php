@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-pink-600 text-center">
        ✍️ Tulis Surat Cinta Romantis
        <div class="mt-2 text-lg font-normal text-pink-400">Ungkapkan Perasaanmu Disini</div>
    </h2>

    <form action="/surat" method="POST" class="space-y-6 bg-white  rounded-xl ">
        @csrf
        
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-pink-700 mb-2">👤 Dari</label>
                <input type="text" name="dari" placeholder="Nama kamu" 
                    class="w-full px-4 py-3 border-2 border-pink-100 rounded-xl focus:border-pink-400 
                           focus:ring-2 focus:ring-pink-200 transition-all duration-200"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-pink-700 mb-2">💖 Untuk</label>
                <input type="text" name="untuk" placeholder="Nama pasanganmu" 
                    class="w-full px-4 py-3 border-2 border-pink-100 rounded-xl focus:border-pink-400 
                           focus:ring-2 focus:ring-pink-200 transition-all duration-200"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-pink-700 mb-2">💌 Isi Surat</label>
                <textarea name="isi" rows="6" placeholder="Tuliskan isi hati kamu..." 
                    class="w-full px-4 py-3 border-2 border-pink-100 rounded-xl focus:border-pink-400 
                           focus:ring-2 focus:ring-pink-200 resize-y min-h-[150px] transition-all duration-200"
                    required></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-pink-700 mb-2">🔑 Password</label>
                <input type="password" name="password" placeholder="Buat password unik" 
                    class="w-full px-4 py-3 border-2 border-pink-100 rounded-xl focus:border-pink-400 
                           focus:ring-2 focus:ring-pink-200 transition-all duration-200"
                    required>
                <p class="mt-2 text-xs text-gray-500">Password ini akan digunakan untuk membuka surat</p>
            </div>
        </div>

        <button type="submit" 
            class="w-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 
                   text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 
                   transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
            🕊️ Kirim Surat Cinta
        </button>
    </form>
</div>
@endsection