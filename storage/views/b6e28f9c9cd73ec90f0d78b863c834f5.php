<?php \Core\View::extend('admin.layouts.app'); ?>

<?php \Core\View::startSection('title', 'Kullanıcıyı Düzenle'); ?>

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('admin.users') ?? ""); ?>"
                class="p-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kullanıcıyı Düzenle</h1>
                <p class="text-slate-500 text-sm"><?php echo e($user['name'] ?? ""); ?> kullanıcısının bilgilerini güncelleyin.</p>
            </div>
        </div>
    </div>

    <?php if(hasFlash('error')): ?>
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
        <p class="text-sm text-red-700 font-medium"><?php echo e(flash('error') ?? ""); ?></p>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.users.update', ['id' => $user['id']]) ?? ""); ?>" method="POST"
        class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Tam İsim <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="<?php echo e($user['name'] ?? ""); ?>" required
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Kullanıcı Adı <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="username" value="<?php echo e($user['username'] ?? ""); ?>" required
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">E-posta Adresi <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" value="<?php echo e($user['email'] ?? ""); ?>" required
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Şifre</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                        placeholder="Değiştirmek istemiyorsanız boş bırakın">
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Rol</label>
                        <select name="role"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none appearance-none transition-all">
                            <option value="user" <?php echo e(($user['role'] ?? '' )==='user' ? 'selected' : '' ?? ""); ?>>Standart
                                Kullanıcı</option>
                            <option value="admin" <?php echo e(($user['role'] ?? '' )==='admin' ? 'selected' : '' ?? ""); ?>>Yönetici
                            </option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Durum</label>
                        <div class="flex items-center gap-4 py-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" value="1" class="sr-only peer" <?php echo $user['status']
                                    ? 'checked' : '' ?? ""; ?>>
                                <div
                                    class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-slate-600">Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <button type="submit"
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Güncelle
                </button>
            </div>
        </div>
    </form>
</div>