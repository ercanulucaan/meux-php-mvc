<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo \Core\View::yieldSection('title', 'Hoşgeldiniz'); ?> - <?php echo e($site_title ?? ""); ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="bg-white text-slate-900 antialiased font-['Inter']">
    <!-- Navigation Container -->
    <div class="relative pt-6 px-4 sm:px-6 lg:px-8 z-50">
        <nav class="relative flex items-center justify-between sm:h-10 lg:justify-start" aria-label="Global">
            <div class="flex items-center flex-grow flex-shrink-0 lg:flex-grow-0">
                <div class="flex items-center justify-between w-full md:w-auto">
                    <a href="<?php echo e(route('home') ?? ""); ?>">
                        <span class="sr-only">Logo</span>
                        <div
                            class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                            M</div>
                    </a>
                </div>
            </div>
            <div class="hidden md:block md:ml-10 md:space-x-8">
                <a href="#" class="font-medium text-slate-500 hover:text-slate-900 transition-colors">Özellikler</a>
                <a href="#" class="font-medium text-slate-500 hover:text-slate-900 transition-colors">Pazaryeri</a>
                <a href="#" class="font-medium text-slate-500 hover:text-slate-900 transition-colors">Şirket</a>
                <?php if(!\Core\Session::has("user_id")): ?>
                <a href="<?php echo e(route('auth.login') ?? ""); ?>" class="font-medium text-blue-600 hover:text-blue-500">Giriş Yap</a>
                <?php endif; ?>
                <?php if(\Core\Session::has("user_id")): ?>
                <a href="<?php echo e(route('admin.dashboard') ?? ""); ?>" class="font-medium text-blue-600 hover:text-blue-500">Panel</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <?php echo $content ?? ""; ?>

    <!-- Simple Footer -->
    <footer class="bg-white border-t border-slate-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-slate-400 text-sm">&copy; <?php echo e(date('Y') ?? ""); ?> <?php echo e($site_title ?? ""); ?>. Tüm hakları saklıdır.</p>
        </div>
    </footer>
</body>

</html>