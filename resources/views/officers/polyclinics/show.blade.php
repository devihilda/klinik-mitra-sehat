<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('officers.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Portal Petugas</a>
            <span>/</span>
            <a href="{{ route('polyclinics.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Manajemen Poli</a>
            <span>/</span>
            <span class="font-semibold text-slate-800 dark:text-slate-100">Detail Poli</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Detail Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Header Banner -->
                <div class="p-6 md:p-8 bg-gradient-to-r from-emerald-600 to-teal-600 flex flex-col md:flex-row md:items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-2xl font-black shadow-lg">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="text-white">
                        <h3 class="text-2xl font-extrabold tracking-tight">{{ $polyclinic->name }}</h3>
                        <p class="text-emerald-100/80 mt-1 line-clamp-2">{{ $polyclinic->description }}</p>
                        <div class="flex items-center space-x-3 mt-3">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold">
                                ID Poli: #{{ $polyclinic->id }}
                            </span>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold">
                                Dibuat: {{ $polyclinic->created_at->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 md:p-8 space-y-8">

                    <!-- Deskripsi Lengkap -->
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Deskripsi Poliklinik</span>
                        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-xl text-slate-800 dark:text-slate-100 leading-relaxed">
                            {{ $polyclinic->description }}
                        </div>
                    </div>

                    <!-- Gambar / Ikon Poli -->
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Gambar / Ikon Poli</span>
                        @if ($polyclinic->image_path)
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-xl space-y-4">
                                <!-- Image Display -->
                                <div class="w-full max-h-72 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                                    <img src="{{ asset($polyclinic->image_path) }}" alt="{{ $polyclinic->name }}" class="w-full max-h-72 object-contain">
                                </div>
                                <!-- File Info -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ basename($polyclinic->image_path) }}</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 break-all font-mono">{{ $polyclinic->image_path }}</p>
                                    </div>
                                    <a href="{{ asset($polyclinic->image_path) }}" target="_blank" class="inline-flex items-center flex-shrink-0 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900 transition text-xs font-semibold">
                                        <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Buka di Tab Baru
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="p-6 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-xl text-center">
                                <div class="mx-auto w-14 h-14 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="h-7 w-7 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada gambar atau ikon yang diunggah untuk poli ini.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Metadata -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-2">
                        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-xl">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Tanggal Dibuat</span>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $polyclinic->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-xl">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Terakhir Diperbarui</span>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $polyclinic->updated_at->translatedFormat('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="px-6 md:px-8 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/20 flex items-center justify-between">
                    <a href="{{ route('polyclinics.index') }}" class="px-5 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        ← Kembali ke Daftar
                    </a>
                    <a href="{{ route('polyclinics.edit', $polyclinic->id) }}" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl shadow-md hover:from-amber-600 hover:to-orange-600 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                        Edit Poli
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
