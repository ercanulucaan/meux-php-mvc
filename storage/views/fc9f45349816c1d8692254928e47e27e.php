<?php \Core\View::extend('auth.layouts.app'); ?>

<?php \Core\View::startSection('title', 'Kayıt Ol'); ?>

<div class="bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
    <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">
            Yeni Hesap Oluşturun
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Veya <a href="<?php echo e(route('auth.login') ?? ""); ?>"
                class="font-medium text-blue-600 hover:text-blue-500 transition-colors">zaten bir hesabınız var
                mı?</a>
        </p>
    </div>

    <?php if(isset($error)): ?>
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
                <p class="text-sm text-red-700"><?php echo e($error ?? ""); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <form class="mt-8 space-y-4" action="<?php echo e(route('auth.register.store') ?? ""); ?>" method="POST">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Ad Soyad</label>
                <input id="name" name="name" type="text" required
                    class="appearance-none relative block w-full px-3 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all"
                    placeholder="John Doe">
            </div>
            <div>
                <label for="username" class="block text-sm font-medium text-slate-700 mb-1">Kullanıcı Adı</label>
                <input id="username" name="username" type="text" required
                    class="appearance-none relative block w-full px-3 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all"
                    placeholder="johndoe">
            </div>
            <div>
                <label for="register-email" class="block text-sm font-medium text-slate-700 mb-1">E-posta
                    Adresi</label>
                <input id="register-email" name="email" type="email" required
                    class="appearance-none relative block w-full px-3 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all"
                    placeholder="john@example.com">
            </div>
            <div>
                <label for="register-password" class="block text-sm font-medium text-slate-700 mb-1">Şifre</label>
                <input id="register-password" name="password" type="password" required
                    class="appearance-none relative block w-full px-3 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all"
                    placeholder="••••••••">
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5 active:translate-y-0 shadow-lg shadow-blue-200">
                Kayıt Ol
            </button>
        </div>
    </form>
</div>