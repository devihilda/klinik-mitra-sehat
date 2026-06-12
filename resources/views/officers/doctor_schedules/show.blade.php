<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Detail Jadwal Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
                <a href="{{ route('dashboard') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition">Portal Petugas</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <a href="{{ route('doctor-schedules.index') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition">Jadwal Dokter</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <span class="text-amber-600 dark:text-amber-400 font-semibold">Detail Jadwal</span>
            </nav>

            <!-- Details Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Card Header with Gradient -->
                <div class="bg-gradient-to-r from-amber-500 to-orange-600 p-6 md:p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-inner">
                            <svg class="h-7 w-7 text-white animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold tracking-tight">{{ $schedule->day }}</h3>
                            <p class="text-amber-100/90 font-medium text-sm mt-0.5">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                            </p>
                        </div>
                    </div>
                    <div>
                        @if ($schedule->is_active)
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/20 backdrop-blur-sm text-white border border-white/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-ping"></span>
                                Praktik Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/10 backdrop-blur-sm text-amber-200 border border-white/10">
                                <span class="w-2 h-2 rounded-full bg-rose-400 mr-2"></span>
                                Praktik Libur
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Column 1: Dokter Detail -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Informasi Dokter</h4>
                            
                            <div class="flex items-start space-x-4 bg-slate-50 dark:bg-slate-900/40 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center font-bold text-base shadow-md shrink-0">
                                    {{ strtoupper(substr($schedule->doctor->name ?? 'DR', 0, 2)) }}
                                </div>
                                <div class="space-y-1">
                                    <div class="font-bold text-slate-850 dark:text-slate-100 text-base">{{ $schedule->doctor->name ?? '-' }}</div>
                                    <div class="text-xs text-amber-600 dark:text-amber-400 font-semibold bg-amber-50 dark:bg-amber-950/30 px-2 py-0.5 rounded-md inline-block">
                                        {{ $schedule->doctor->specialization ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-2 font-mono">
                                        SIP: {{ $schedule->doctor->sip ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Alokasi & Pelayanan -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Alokasi & Poliklinik</h4>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                                    <span class="text-xs text-slate-400 dark:text-slate-550 block mb-1">Poliklinik</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 text-base block">{{ $schedule->polyclinic->name ?? '-' }}</span>
                                </div>

                                <div class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                                    <span class="text-xs text-slate-400 dark:text-slate-550 block mb-1">Kuota Harian</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 text-base block">{{ $schedule->quota }} Pasien</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Meta & Status Audit Alert (Optional / Contextual) -->
                    <div class="mt-8 p-4 bg-slate-50 dark:bg-slate-900/30 rounded-xl border border-slate-150 dark:border-slate-700/50 flex items-start space-x-3">
                        <svg class="h-5 w-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-xs text-slate-500 dark:text-slate-400 space-y-1">
                            <p class="font-semibold text-slate-700 dark:text-slate-300">Catatan Sistem Manajemen Jadwal</p>
                            <p>Dibuat pada {{ $schedule->created_at ? $schedule->created_at->translatedFormat('d F Y H:i') : '-' }} WIB. Diperbarui terakhir pada {{ $schedule->updated_at ? $schedule->updated_at->translatedFormat('d F Y H:i') : '-' }} WIB.</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="{{ route('doctor-schedules.index') }}" class="inline-flex items-center px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                            <svg class="h-4.5 w-4.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('doctor-schedules.edit', $schedule->id) }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl shadow-md hover:from-amber-600 hover:to-orange-700 transition transform hover:-translate-y-0.5 text-sm">
                                <svg class="h-4.5 w-4.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Jadwal
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
