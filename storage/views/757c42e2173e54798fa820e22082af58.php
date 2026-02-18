<?php \Core\View::extend('admin.layouts.app'); ?>

<?php \Core\View::startSection('title', 'Gösterge Paneli'); ?>

<div class="space-y-8">
    <!-- Breadcrumbs & Title -->
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
        <p class="text-slate-500 mt-1">Uygulamanızın genel durumuna bir göz atın.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
            class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Toplam Kullanıcı</p>
                <p class="text-2xl font-bold text-slate-900"><?php echo e($stats['users_count'] ?? 0 ?? ""); ?></p>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Aktif Oturumlar</p>
                <p class="text-2xl font-bold text-slate-900">12</p>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Bekleyen İşlemler</p>
                <p class="text-2xl font-bold text-slate-900">3</p>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Sistem Logları</p>
                <p class="text-2xl font-bold text-slate-900">1.2k</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions / Table Section Example -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Son Hareketler</h3>
            <button class="text-sm font-semibold text-blue-600 hover:text-blue-700">Tümünü Gör</button>
        </div>
        <div class="p-0">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kullanıcı</th>
                        <th class="px-6 py-4">İşlem</th>
                        <th class="px-6 py-4">Tarih</th>
                        <th class="px-6 py-4">Durum</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr>
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-700">
                                AD</div>
                            <span class="text-sm font-medium text-slate-900"><?php echo e(\Core\Session::get('user_name') ?? ""); ?></span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">Sisteme giriş yapıldı</td>
                        <td class="px-6 py-4 text-sm text-slate-500">Şimdi</td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Başarılı</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>