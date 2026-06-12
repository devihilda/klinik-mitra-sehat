<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Detail Pendaftaran Antrean') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
                <a href="{{ route('dashboard') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Portal Petugas</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <a href="{{ route('queues.index') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Manajemen Antrean</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <span class="text-violet-600 dark:text-violet-400 font-semibold">Detail Antrean</span>
            </nav>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Left Column: Big Ticket Card -->
                <div class="md:col-span-1 flex flex-col space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden relative">
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-violet-500/10 rounded-full blur-xl"></div>
                        <div class="bg-gradient-to-r from-violet-500 to-fuchsia-600 p-6 text-center text-white">
                            <span class="text-xs uppercase tracking-wider font-bold opacity-85">Nomor Antrean</span>
                            <div class="text-6xl font-black my-2">{{ str_pad($queue->queue_number, 2, '0', STR_PAD_LEFT) }}</div>
                            <span class="text-xs px-2.5 py-1 rounded bg-white/20 font-bold tracking-wide uppercase">
                                {{ $queue->status }}
                            </span>
                        </div>
                        <div class="p-6 space-y-4 border-t border-dashed border-slate-200 dark:border-slate-700 relative">
                            <!-- Circular punch cuts to make it look like a physical ticket -->
                            <div class="absolute -left-3 -top-3 w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-900"></div>
                            <div class="absolute -right-3 -top-3 w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-900"></div>

                            <div class="text-center font-mono">
                                <span class="text-xs text-slate-400 block">TANGGAL ANTRIAN</span>
                                <span class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ \Carbon\Carbon::parse($queue->queue_date)->translatedFormat('d F Y') }}</span>
                            </div>

                            <hr class="border-slate-100 dark:border-slate-700">

                            <div class="space-y-3">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Poliklinik</span>
                                    <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $queue->polyclinic->name ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Dokter Pemeriksa</span>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $queue->doctor->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Detail Information Cards -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Patient Info Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 space-y-4">
                        <div class="flex items-center space-x-3 pb-3 border-b border-slate-100 dark:border-slate-700/50">
                            <div class="p-2 bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Informasi Pasien</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-sm">
                            <div>
                                <span class="text-slate-400 block">Nama Lengkap</span>
                                <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $queue->patient->user->name ?? '-' }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">NIK</span>
                                <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $queue->patient->nik ?? '-' }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">No. Telepon</span>
                                <strong class="text-slate-800 dark:text-slate-200">{{ $queue->patient->phone ?? '-' }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Jenis Kelamin</span>
                                <strong class="text-slate-800 dark:text-slate-200 capitalize">{{ $queue->patient->gender ?? '-' }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Tanggal Lahir</span>
                                <strong class="text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($queue->patient->birth_date)->translatedFormat('d M Y') }}</strong>
                            </div>
                            <div class="col-span-2">
                                <span class="text-slate-400 block">Alamat Tinggal</span>
                                <p class="text-slate-700 dark:text-slate-300 mt-0.5">{{ $queue->patient->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Complaint Info Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 space-y-4">
                        <div class="flex items-center space-x-3 pb-3 border-b border-slate-100 dark:border-slate-700/50">
                            <div class="p-2 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Keluhan Utama</h3>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800/80">
                            <p class="text-slate-700 dark:text-slate-300 text-sm whitespace-pre-line font-medium italic">
                                "{{ $queue->complaint ?? 'Tidak ada keluhan tertulis.' }}"
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action Bar -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-100 dark:border-slate-700/50">
                <a href="{{ route('queues.index') }}" class="inline-flex items-center px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('queues.edit', $queue->id) }}" class="inline-flex items-center px-5 py-3 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl shadow-md hover:shadow-violet-500/10 transition duration-150 ease-in-out text-sm">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Ubah Antrean
                    </a>
                    <form action="{{ route('queues.destroy', $queue->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus antrean ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-5 py-3 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-900 text-rose-600 dark:text-rose-400 font-bold rounded-xl transition text-sm">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
