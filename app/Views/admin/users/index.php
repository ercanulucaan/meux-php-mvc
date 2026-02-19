@extends('admin.layouts.app')

@section('title', 'Kullanıcı Yönetimi')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kullanıcılar</h1>
            <p class="text-slate-500 mt-1">Sistemdeki tüm kullanıcıları yönetin ve yetkilendirin.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-200 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Yeni Kullanıcı Ekle
        </a>
    </div>

    @if(hasFlash('success'))
    <div class="bg-emerald-50 border-emerald-500 border-l-4 p-4 rounded-r-xl shadow-sm">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-sm text-emerald-800 font-semibold">{{ flash('success') }}</p>
        </div>
    </div>
    @endif

    @if(hasFlash('error'))
    <div class="bg-red-50 border-red-500 border-l-4 p-4 rounded-r-xl shadow-sm">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <p class="text-sm text-red-800 font-semibold">{{ flash('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Users Table Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kullanıcı</th>
                        <th class="px-6 py-4">E-posta</th>
                        <th class="px-6 py-4">Rol</th>
                        <th class="px-6 py-4">Durum</th>
                        <th class="px-6 py-4">Kayıt Tarihi</th>
                        <th class="px-6 py-4 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if(isset($users) && count($users) > 0)
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-sm font-bold text-indigo-600 border border-slate-200">
                                    {{ strtoupper(substr($user['name'] ?? '', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $user['name'] ?? '' }}</div>
                                    <div class="text-xs text-slate-500">@ {{ $user['username'] ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                            {{ $user['email'] ?? '' }}
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ ($user['role'] ?? '') === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($user['role'] ?? '') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if(($user['status'] ?? 0) == 1)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                            @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Pasif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ date('d.m.Y', strtotime($user['created_at'] ?? 'now')) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', ['id' => $user['id'] ?? 0]) }}"
                                    class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                    title="Düzenle">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.users.delete', ['id' => $user['id'] ?? 0]) }}"
                                    method="POST" class="inline" onsubmit="return confirm('Emin misiniz?')">
                                    <button type="submit"
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                        title="Sil">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">
                            Henüz kullanıcı bulunmuyor.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>