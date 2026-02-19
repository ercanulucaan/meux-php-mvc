<?php \Core\View::extend('guests.layouts.app'); ?>

<?php \Core\View::startSection('title', 'Anasayfa'); ?>

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
                        <?php echo e($site_title ?? ""); ?> ile projelerinizi daha hızlı, güvenli ve modüler bir yapıda geliştirin.
                        Tailwind CSS ve gelişmiş Service Provider mimarisiyle tanışın.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="<?php echo e(route('auth.register') ?? ""); ?>"
                                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-slate-900 hover:bg-slate-800 md:py-4 md:text-lg md:px-10 transition-all transform hover:-translate-y-1 shadow-lg shadow-slate-200">
                                Hemen Başlayın
                            </a>
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