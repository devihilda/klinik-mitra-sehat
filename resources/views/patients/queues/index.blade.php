<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Antrean Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome/Header Card -->
            <div class="bg-gradient-to-r from-violet-500 via-purple-600 to-fuchsia-600 rounded-2xl shadow-xl overflow-hidden text-white relative">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-black/10 rounded-full blur-2xl"></div>

                <div class="p-8 md:p-10 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight">
                            Manajemen Antrean Kunjungan
                        </h1>
                        <p class="text-violet-100/90 mt-2 text-sm max-w-xl">
                            Daftarkan antrean baru secara mandiri, lihat tiket digital Anda, atau pantau status pemeriksaan Anda secara real-time.
                        </p>
                    </div>
                    <a href="{{ route('patients.queues.create') }}" class="px-6 py-3.5 bg-white text-violet-700 font-bold rounded-xl shadow-md hover:bg-violet-50 transition duration-150 ease-in-out transform hover:-translate-y-0.5 whitespace-nowrap">
                        Daftar Antrean Baru
                    </a>
                </div>
            </div>

            <!-- Flash Message -->
            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 p-4 rounded-xl flex items-center space-x-3 shadow-sm transition-all duration-300">
                    <div class="p-1.5 bg-emerald-100 dark:bg-emerald-900 rounded-lg text-emerald-600 dark:text-emerald-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Queues Section -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 px-1">Daftar Antrean Anda</h3>

                @if ($queues->count() > 0)
                    <!-- Card Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($queues as $queue)
                            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                                <!-- Card Upper Section -->
                                <div>
                                    <!-- Header of the Card -->
                                    <div class="p-5 bg-slate-50 dark:bg-slate-900/40 flex items-center justify-between border-b border-slate-100 dark:border-slate-700/50">
                                        <div class="flex items-center space-x-2">
                                            <span class="p-1.5 bg-violet-100 dark:bg-violet-950/60 rounded text-violet-600 dark:text-violet-400">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                                </svg>
                                            </span>
                                            <span class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400">TICKET</span>
                                        </div>

                                        <!-- Status Badge -->
                                        @if ($queue->status === 'menunggu')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-200/50 dark:border-amber-900/30">
                                                Menunggu
                                            </span>
                                        @elseif ($queue->status === 'diperiksa')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-800 dark:text-blue-300 border border-blue-200/50 dark:border-blue-900/30">
                                                Diperiksa
                                            </span>
                                        @elseif ($queue->status === 'selesai')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-900/30">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300 border border-rose-200/50 dark:border-rose-900/30">
                                                Batal
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Content Area -->
                                    <div class="p-6 space-y-4">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <h4 class="text-lg font-bold text-slate-800 dark:text-slate-100 tracking-tight">{{ $queue->polyclinic->name ?? '-' }}</h4>
                                                <p class="text-xs text-violet-600 dark:text-violet-400 font-semibold mt-0.5">{{ $queue->doctor->name ?? '-' }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-slate-400 text-[10px] uppercase font-bold block">No. Antrean</span>
                                                <span class="text-2xl font-black text-violet-600 dark:text-violet-400">{{ str_pad($queue->queue_number, 2, '0', STR_PAD_LEFT) }}</span>
                                            </div>
                                        </div>

                                        <hr class="border-slate-100 dark:border-slate-700/50">

                                        <div class="space-y-2 text-xs">
                                            <div class="flex justify-between">
                                                <span class="text-slate-400">Tanggal Pemeriksaan:</span>
                                                <strong class="text-slate-700 dark:text-slate-300 font-mono">{{ \Carbon\Carbon::parse($queue->queue_date)->translatedFormat('d M Y') }}</strong>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-400">Jadwal Praktik:</span>
                                                <strong class="text-slate-700 dark:text-slate-300">{{ $queue->doctorSchedule->day ?? '-' }}, {{ \Carbon\Carbon::parse($queue->doctorSchedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($queue->doctorSchedule->end_time)->format('H:i') }} WIB</strong>
                                            </div>
                                        </div>

                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800/80 mt-2">
                                            <span class="text-[9px] uppercase font-bold text-slate-400 block mb-0.5">Keluhan Utama</span>
                                            <p class="text-slate-600 dark:text-slate-400 text-xs italic line-clamp-2">
                                                "{{ $queue->complaint ?? 'Tidak ada keluhan tertulis.' }}"
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Footer -->
                                <div class="px-6 pb-6 pt-2">
                                    <a href="{{ route('patients.queues.show', $queue->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl transition shadow-sm text-sm">
                                        Lihat Tiket Digital
                                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-16 text-center">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-violet-100 to-fuchsia-100 dark:from-violet-900/30 dark:to-fuchsia-900/30 rounded-3xl flex items-center justify-center mb-6 shadow-inner animate-pulse">
                            <svg class="h-12 w-12 text-violet-600 dark:text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3m-3-4.5h5.25m3-.75H18a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 19.5v-9A2.25 2.25 0 016 8.25h3.75a1.125 1.125 0 01.75.3M10.5 2.25H12a2.25 2.25 0 012.25 2.25v2.25H10.5V2.25z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-700 dark:text-slate-200">Belum Ada Antrean Terdaftar</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-sm mx-auto">
                            Anda belum memiliki riwayat pendaftaran antrean di Klinik Mitra Sehat.
                        </p>
                        <a href="{{ route('patients.queues.create') }}" class="inline-flex items-center mt-6 px-6 py-3 bg-gradient-to-r from-violet-500 to-fuchsia-600 text-white font-bold rounded-xl shadow-md hover:from-violet-600 hover:to-fuchsia-700 transition">
                            Daftar Antrean Pertama Anda
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
