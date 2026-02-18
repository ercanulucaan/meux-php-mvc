@extends('auth.layouts.app')

@section('title', 'Giriş Yap')

<div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
    <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">
            Hesabınıza Giriş Yapın
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Veya <a href="{{ route('auth.register') }}"
                class="font-medium text-blue-600 hover:text-blue-500 transition-colors">yeni bir hesap oluşturun</a>
        </p>
    </div>

    @if(isset($error))
    <div class="bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ $error }}</p>
            </div>
        </div>
    </div>
    @endif

    <form class="mt-8 space-y-6" action="{{ route('auth.login.store') }}" method="POST">
        <div class="rounded-md shadow-sm -space-y-px">
            <div class="mb-4">
                <label for="email-address" class="block text-sm font-medium text-slate-700 mb-1">E-posta
                    Adresi</label>
                <input id="email-address" name="email" type="email" required
                    class="appearance-none relative block w-full px-3 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all"
                    placeholder="admin@example.com">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Şifre</label>
                <input id="password" name="password" type="password" required
                    class="appearance-none relative block w-full px-3 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all"
                    placeholder="••••••••">
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember-me" name="remember-me" type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                <label for="remember-me" class="ml-2 block text-sm text-slate-900">Beni hatırla</label>
            </div>

            <div class="text-sm">
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">Şifremi
                    unuttum</a>
            </div>
        </div>

        <div>
            <button type="submit"
                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                Giriş Yap
            </button>
        </div>
    </form>
</div>
</div>