<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Detail Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
                <a href="{{ route('dashboard') }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition">Portal Petugas</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <a href="{{ route('doctors.index') }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition">Manajemen Dokter</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <span class="text-purple-600 dark:text-purple-400 font-semibold">Detail</span>
            </nav>

            <!-- Detail Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Card Header with gradient -->
                <div class="bg-gradient-to-r from-purple-600 to-cyan-600 p-8 relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>

                    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-5">
                        <!-- Avatar -->
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg">
                            {{ strtoupper(substr($doctor->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-white">{{ $doctor->name }}</h3>
                            <p class="text-purple-100/90 text-sm mt-1">{{ $doctor->specialization }}</p>
                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-bold rounded-lg">ID #{{ $doctor->id }}</span>
                                @php
                                    $statusHeaderColors = [
                                        'aktif' => 'bg-emerald-400/30 text-emerald-100',
                                        'cuti' => 'bg-amber-400/30 text-amber-100',
                                        'tidak aktif' => 'bg-rose-400/30 text-rose-100',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg {{ $statusHeaderColors[$doctor->status] ?? 'bg-white/20 text-white' }}">
                                    {{ ucfirst($doctor->status) }}
                                </span>
                                <span class="px-2.5 py-1 bg-white/10 text-purple-100 text-xs font-medium rounded-lg">
                                    Bergabung {{ $doctor->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nomor SIP -->
                        <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-5 border border-slate-100 dark:border-slate-700/50">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="p-2 bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 rounded-lg">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nomor SIP</span>
                            </div>
                            <p class="text-base font-bold text-slate-800 dark:text-slate-100 font-mono">{{ $doctor->sip }}</p>
                        </div>

                        <!-- Poliklinik -->
                        <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-5 border border-slate-100 dark:border-slate-700/50">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="p-2 bg-cyan-100 dark:bg-cyan-900/50 text-cyan-600 dark:text-cyan-400 rounded-lg">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Poliklinik</span>
                            </div>
                            <p class="text-base font-bold text-slate-800 dark:text-slate-100">{{ $doctor->polyclinic->name ?? '-' }}</p>
                        </div>

                        <!-- No. HP -->
                        <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-5 border border-slate-100 dark:border-slate-700/50">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-lg">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">No. HP</span>
                            </div>
                            <p class="text-base font-bold text-slate-800 dark:text-slate-100">{{ $doctor->phone }}</p>
                        </div>

                        <!-- Status -->
                        <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-5 border border-slate-100 dark:border-slate-700/50">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-lg">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</span>
                            </div>
                            @php
                                $statusColors = [
                                    'aktif' => 'bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300',
                                    'cuti' => 'bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300',
                                    'tidak aktif' => 'bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold {{ $statusColors[$doctor->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst($doctor->status) }}
                            </span>
                        </div>

                        <!-- Dibuat pada -->
                        <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-5 border border-slate-100 dark:border-slate-700/50">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="p-2 bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dibuat pada</span>
                            </div>
                            <p class="text-base font-bold text-slate-800 dark:text-slate-100">{{ $doctor->created_at->translatedFormat('d F Y, H:i') }}</p>
                        </div>

                        <!-- Terakhir diperbarui -->
                        <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-5 border border-slate-100 dark:border-slate-700/50">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="p-2 bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Terakhir diperbarui</span>
                            </div>
                            <p class="text-base font-bold text-slate-800 dark:text-slate-100">{{ $doctor->updated_at->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 md:px-8 pb-6 md:pb-8 flex items-center justify-between">
                    <a href="{{ route('doctors.index') }}" class="inline-flex items-center px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                    <a href="{{ route('doctors.edit', $doctor->id) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold rounded-xl shadow-md hover:from-purple-700 hover:to-cyan-700 transition transform hover:-translate-y-0.5 text-sm">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Dokter
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
