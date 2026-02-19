<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo \Core\View::yieldSection('title'); ?> - <?php echo e($site_title ?? ""); ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- File Picker CSS -->
    <link rel="stylesheet" href="<?php echo e(url('public/css/filepicker.css') ?? ""); ?>">
    @yields('styles')
</head>

<body class="bg-slate-50 text-slate-900 antialiased font-['Inter']">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-slate-400 flex flex-col fixed inset-y-0 shadow-2xl z-50">
            <div class="p-6 flex items-center space-x-3 text-white border-b border-white/5">
                <div
                    class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-900 font-bold text-xl">
                    M</div>
                <span class="font-bold tracking-tight text-lg"><?php echo e($site_title ?? ""); ?></span>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="<?php echo e(route('admin.dashboard') ?? ""); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-600 text-white font-medium shadow-lg shadow-blue-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/5 hover:text-white transition-all group">
                    <svg class="w-5 h-5 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span>Kullanıcılar</span>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/5 hover:text-white transition-all group">
                    <svg class="w-5 h-5 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Ayarlar</span>
                </a>
            </nav>

            <div class="p-6 border-t border-white/5">
                <a href="<?php echo e(route('auth.logout') ?? ""); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-400/10 transition-all font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span>Çıkış Yap</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 ml-64 flex flex-col">
            <!-- Top Header -->
            <header
                class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
                <div class="flex items-center space-x-4">
                    <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                    <span class="text-slate-500 font-medium">Hoş geldin, <span class="text-slate-900"><?php echo e(\Core\Session::get('user_name') ?? ""); ?></span></span>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="<?php echo e(route('home') ?? ""); ?>"
                        class="text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-blue-600 transition-colors">Siteyi
                        Görüntüle</a>
                    <div
                        class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-700">
                        AD</div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-8">
                <?php echo $content ?? ""; ?>
            </main>
        </div>
    </div>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?php echo e(url('public/js/filepicker.js') ?? ""); ?>"></script>
    <?php echo \Core\View::yieldSection('scripts'); ?>
</body>

</html>