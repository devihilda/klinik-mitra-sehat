<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Manajemen Data Poli') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Header Bar: Title + Add Button -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Daftar Poli</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola seluruh data poliklinik yang tersedia di Klinik Mitra Sehat</p>
                </div>
                <a href="{{ route('polyclinics.create') }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl shadow-md hover:from-emerald-700 hover:to-teal-700 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Poli Baru
                </a>
            </div>

            <!-- Flash Message -->
            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 p-4 rounded-xl flex items-center space-x-3">
                    <div class="p-1 bg-emerald-100 dark:bg-emerald-900 rounded-lg text-emerald-600 dark:text-emerald-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Polyclinics Grid -->
            @if ($polyclinics->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($polyclinics as $polyclinic)
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden group hover:shadow-lg hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all duration-300">
                            <!-- Image Area -->
                            <div class="h-44 w-full relative overflow-hidden bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/30 dark:to-teal-950/30">
                                @if ($polyclinic->image_path)
                                    <img src="{{ asset($polyclinic->image_path) }}" alt="{{ $polyclinic->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <!-- Placeholder Icon -->
                                    <div class="w-full h-full flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/20 mb-3">
                                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-emerald-500/70 dark:text-emerald-400/50">Belum ada gambar</span>
                                    </div>
                                @endif
                                <!-- ID Badge -->
                                <div class="absolute top-3 right-3 px-2.5 py-1 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm rounded-lg text-xs font-bold text-emerald-700 dark:text-emerald-400 shadow-sm">
                                    #{{ $polyclinic->id }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <h4 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-1 group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">
                                    {{ $polyclinic->name }}
                                </h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                    {{ $polyclinic->description }}
                                </p>

                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-2 mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                    <!-- Detail -->
                                    <a href="{{ route('polyclinics.show', $polyclinic->id) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition text-xs font-semibold">
                                        <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Detail
                                    </a>
                                    <!-- Edit -->
                                    <a href="{{ route('polyclinics.edit', $polyclinic->id) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900 transition text-xs font-semibold">
                                        <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <!-- Hapus -->
                                    <form action="{{ route('polyclinics.destroy', $polyclinic->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus poli ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900 transition text-xs font-semibold">
                                            <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                    <div class="p-12 text-center">
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 rounded-2xl flex items-center justify-center mb-5 shadow-inner">
                            <svg class="h-10 w-10 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-700 dark:text-slate-200">Belum Ada Data Poli</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-sm mx-auto">Belum ada poliklinik yang terdaftar di sistem. Klik tombol di bawah untuk menambahkan poli pertama.</p>
                        <a href="{{ route('polyclinics.create') }}" class="inline-flex items-center mt-6 px-5 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl shadow-md hover:from-emerald-700 hover:to-teal-700 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Poli Baru
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
