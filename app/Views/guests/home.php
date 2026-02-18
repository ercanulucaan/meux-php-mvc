@extends('guests.layouts.app')

@section('title', 'Anasayfa')

<!-- Hero Section -->
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2"
                fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                <polygon points="50,0 100,0 50,100 0,100" />
            </svg>

            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Modern PHP Deneyimi</span>
                        <span class="block text-blue-600 xl:inline">Micro MVC Framework</span>
                    </h1>
                    <p
                        class="mt-3 text-base text-slate-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        {{ $site_title }} ile projelerinizi daha hızlı, güvenli ve modüler bir yapıda geliştirin.
                        Tailwind CSS ve gelişmiş Service Provider mimarisiyle tanışın.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="{{ route('auth.register') }}"
                                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-slate-900 hover:bg-slate-800 md:py-4 md:text-lg md:px-10 transition-all transform hover:-translate-y-1 shadow-lg shadow-slate-200">
                                Hemen Başlayın
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button id="file-picker"
                                    class="flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-semibold transition-all shadow-lg hover:shadow-indigo-200 group">
                                    <span class="text-xl group-hover:scale-110 transition-transform">🖼️</span>
                                    Dosya Seç (Tekil)
                                </button>
                                <button id="file-picker-multi"
                                    class="flex items-center justify-center gap-2 px-6 py-4 bg-slate-800 hover:bg-slate-900 text-white rounded-2xl font-semibold transition-all shadow-lg hover:shadow-slate-200 group">
                                    <span class="text-xl group-hover:scale-110 transition-transform">📂</span>
                                    Dosya Seç (Çoğul)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full"
            src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2850&q=80"
            alt="Work culture">
    </div>
</div>

<!-- Features Section -->
<div class="py-12 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
            <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Kusursuz Yapı</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                Daha iyi bir geliştirme platformu
            </p>
        </div>

        <div class="mt-10">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                <div class="relative">
                    <dt>
                        <div
                            class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-white border border-slate-100 shadow-sm text-slate-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-bold text-slate-900">Hızlı Başlangıç</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-slate-500">
                        Oturmuş klasör yapısı ve hazır helper'lar ile projeniz dakikalar içinde hazır hale gelir.
                    </dd>
                </div>

                <div class="relative">
                    <dt>
                        <div
                            class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-white border border-slate-100 shadow-sm text-slate-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-bold text-slate-900">Güvenli Yapı</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-slate-500">
                        SQL Injection ve XSS saldırılarına karşı optimize edilmiş koruma mekanizmalarıyla içiniz rahat
                        olsun.
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- File Picker Demo Section -->
<div class="py-24 bg-slate-50 border-y border-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-4">
                Modern Dosya Seçici
            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                Projelerinizde kullanabileceğiniz, tamamen özelleştirilebilir ve API uyumlu modal dosya yöneticisi.
            </p>
        </div>

        <div
            class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 md:p-12 border border-slate-100 flex flex-col items-center">
            <!-- Önizleme Kartı -->
            <div
                class="relative group w-full max-w-sm aspect-square mb-10 overflow-hidden rounded-2xl bg-slate-100 border-2 border-dashed border-slate-200 group-hover:border-blue-400 transition-colors">
                <img id="selected-preview" src="" alt="Önizleme" class="w-full h-full object-cover hidden">
                <div id="preview-placeholder"
                    class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="font-medium">Henüz Görsel Seçilmedi</span>
                </div>
            </div>

            <!-- Bilgi Formu -->
            <div class="w-full max-w-md space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-2">Dosya
                        URL</label>
                    <div class="relative">
                        <input type="text" id="selected-file-path" readonly
                            class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none font-mono text-sm"
                            placeholder="Seçilen dosya yolu burada görünecek...">
                        <div class="absolute right-3 top-3 text-slate-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                    </div>
                </div>

                <button id="file-picker"
                    class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg shadow-slate-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span>Medya Kütüphanesini Aç</span>
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function () {
        // Gelişmiş File Picker Başlatma
        $('#file-picker').filePicker({
            title: 'Medya Seçici',
            apiUrl: '{{ url("api/library") }}',
            uploadUrl: '{{ url("api/upload") }}',
            deleteUrl: '{{ url("api/delete") }}',
            inputTarget: '#selected-file-path',
            previewTarget: '#selected-preview',
            onSelect: function (url) {
                console.log('Seçilen Dosya:', url);
            }
        });

        $('#file-picker-multi').filePicker({
            title: 'Çoklu Medya Seçici',
            apiUrl: '{{ url("api/library") }}',
            uploadUrl: '{{ url("api/upload") }}',
            deleteUrl: '{{ url("api/delete") }}',
            multiple: true,
            onSelect: function (urls) {
                console.log('Seçilen Dosyalar:', urls);
                alert(urls.length + ' dosya seçildi:\n' + urls.join('\n'));
            }
        });
    });
</script>
@endsection