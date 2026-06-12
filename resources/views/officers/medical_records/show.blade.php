<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('officers.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Portal Petugas</a>
            <span>/</span>
            <a href="{{ route('medical-records.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Rekam Medis</a>
            <span>/</span>
            <span class="font-semibold text-slate-800 dark:text-slate-100">Detail Rekam Medis</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Detail Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Header Banner -->
                <div class="p-6 md:p-8 bg-gradient-to-r from-violet-600 to-fuchsia-600 flex flex-col md:flex-row md:items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-2xl font-black">
                        MR
                    </div>
                    <div class="text-white">
                        <h3 class="text-2xl font-extrabold tracking-tight">Rekam Medis: {{ $record->patient->user->name ?? '-' }}</h3>
                        <p class="text-violet-100/80 mt-1">Tanggal Kunjungan: {{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d F Y') }}</p>
                        <div class="flex items-center space-x-3 mt-3">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold">
                                ID Rekam Medis: #{{ $record->id }}
                            </span>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold">
                                ID Pasien: #{{ $record->patient_id }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="p-6 md:p-8 space-y-8">
                    <!-- Seksi Data Pasien & Pemeriksa -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100 dark:border-slate-700/50">
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Identitas Pasien</span>
                            <h4 class="text-base font-bold text-slate-800 dark:text-slate-100">{{ $record->patient->user->name ?? '-' }}</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $record->patient->phone ?? '-' }} | {{ ucfirst($record->patient->gender ?? '-') }}</p>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Pemeriksa / Petugas</span>
                            <h4 class="text-base font-bold text-slate-800 dark:text-slate-100">{{ $record->officer->name ?? 'Sistem' }}</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Role: {{ ucfirst($record->officer->role ?? '-') }}</p>
                        </div>
                    </div>

                    <!-- Seksi Diagnosis & Tindakan Medis (TITIK RAWAN XSS) -->
                    <div class="space-y-6">
                        <!-- Diagnosis -->
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">1. Diagnosis</span>
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-xl prose dark:prose-invert max-w-none text-slate-800 dark:text-slate-100">
                                {{--
                                    Kebutuhan Praktikum (Stored XSS):
                                    Sengaja dirender mentah-mentah menggunakan {!! !!}
                                    tanpa escaping HTML. Script berbahaya apa pun yang tersimpan di kolom 'diagnosis'
                                    akan dieksekusi secara otomatis oleh browser saat petugas memuat halaman detail ini.
                                --}}
                                {!! $record->diagnosis !!}
                            </div>
                        </div>

                        <!-- Tindakan / Terapi -->
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">2. Tindakan / Terapi / Resep</span>
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-xl prose dark:prose-invert max-w-none text-slate-800 dark:text-slate-100">
                                {{-- Kebutuhan Praktikum (Stored XSS) --}}
                                {!! $record->treatment !!}
                            </div>
                        </div>

                        <!-- Catatan Tambahan -->
                        @if ($record->notes)
                            <div>
                                <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">3. Catatan Tambahan</span>
                                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-xl prose dark:prose-invert max-w-none text-slate-800 dark:text-slate-100">
                                    {{-- Kebutuhan Praktikum (Stored XSS) --}}
                                    {!! $record->notes !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="px-6 md:px-8 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/20 flex items-center justify-between">
                    <a href="{{ route('medical-records.index') }}" class="px-5 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        ← Kembali ke Daftar
                    </a>
                    <a href="{{ route('medical-records.edit', $record->id) }}" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl shadow-md hover:from-amber-600 hover:to-orange-600 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                        Edit Rekam Medis
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
