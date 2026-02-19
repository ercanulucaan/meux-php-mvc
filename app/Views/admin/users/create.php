@extends('admin.layouts.app')

@section('title', 'Yeni Kullanıcı Ekle')

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users') }}"
                class="p-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Yeni Kullanıcı Ekle</h1>
                <p class="text-slate-500 text-sm">Sisteme yeni bir yönetici veya üye tanımlayın.</p>
            </div>
        </div>
    </div>

    @if(hasFlash('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
        <p class="text-sm text-red-700 font-medium">{{ flash('error') }}</p>
    </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Tam İsim <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                            placeholder="Ahmet Yılmaz">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Kullanıcı Adı <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="username" required
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                            placeholder="ahmet_y">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">E-posta Adresi <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                        placeholder="ahmet@site.com">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Şifre <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                        placeholder="********">
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
                            <option value="user">Standart Kullanıcı</option>
                            <option value="admin">Yönetici</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Durum</label>
                        <div class="flex items-center gap-4 py-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" value="1" checked class="sr-only peer">
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
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                        </path>
                    </svg>
                    Kullanıcıyı Kaydet
                </button>
            </div>
        </div>
    </form>
</div>