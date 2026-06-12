<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Tiket Antrean Digital') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400 px-2 sm:px-0">
                <a href="{{ route('dashboard') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Portal Pasien</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <a href="{{ route('patients.queues.index') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Antrean Saya</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <span class="text-violet-600 dark:text-violet-400 font-semibold">Tiket Digital</span>
            </nav>

            <!-- Digital Ticket Container -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700/50 relative">
                <!-- Glowing Background Accents -->
                <div class="absolute -right-20 -top-20 w-44 h-44 bg-violet-600/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-20 -bottom-20 w-44 h-44 bg-fuchsia-600/10 rounded-full blur-2xl"></div>

                <!-- Ticket Header -->
                <div class="bg-gradient-to-r from-violet-600 to-fuchsia-600 p-6 text-center text-white relative">
                    <h3 class="text-lg font-black tracking-wide">KLINIK MITRA SEHAT</h3>
                    <p class="text-[10px] text-violet-100 tracking-wider font-semibold uppercase mt-0.5">Struk Resmi Antrean Pasien</p>
                </div>

                <!-- Ticket Body / Numbers -->
                <div class="p-8 text-center relative border-b border-dashed border-slate-200 dark:border-slate-700">
                    <span class="text-xs uppercase tracking-widest font-bold text-slate-400 dark:text-slate-500 block">Nomor Antrean Anda</span>
                    
                    <!-- HUGE queue number -->
                    <div class="text-8xl font-black my-4 text-transparent bg-clip-text bg-gradient-to-br from-violet-600 to-fuchsia-600 tracking-tighter">
                        {{ str_pad($queue->queue_number, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-violet-50 dark:bg-violet-950/40 text-violet-700 dark:text-violet-300 border border-violet-100 dark:border-violet-900/30">
                        {{ $queue->status }}
                    </div>
                </div>

                <!-- Ticket Perforations / Punch cuts -->
                <div class="absolute left-0 top-[220px] w-6 h-12 bg-slate-100 dark:bg-slate-900 rounded-r-full -translate-x-3 shadow-inner"></div>
                <div class="absolute right-0 top-[220px] w-6 h-12 bg-slate-100 dark:bg-slate-900 rounded-l-full translate-x-3 shadow-inner"></div>

                <!-- Ticket Details Area -->
                <div class="p-8 space-y-5">
                    
                    <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-sm">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Nama Pasien</span>
                            <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $queue->patient->user->name ?? '-' }}</strong>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Tanggal Kunjungan</span>
                            <strong class="text-slate-800 dark:text-slate-200 font-mono font-bold">{{ \Carbon\Carbon::parse($queue->queue_date)->translatedFormat('d M Y') }}</strong>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Poli Spesialis</span>
                            <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $queue->polyclinic->name ?? '-' }}</strong>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Hari Praktik</span>
                            <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $queue->doctorSchedule->day ?? '-' }}</strong>
                        </div>
                        <div class="col-span-2">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Dokter Pemeriksa</span>
                            <strong class="text-violet-600 dark:text-violet-400 font-bold">{{ $queue->doctor->name ?? '-' }}</strong>
                        </div>
                        <div class="col-span-2">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Waktu Pelayanan</span>
                            <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ \Carbon\Carbon::parse($queue->doctorSchedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($queue->doctorSchedule->end_time)->format('H:i') }} WIB</strong>
                        </div>
                    </div>

                    <hr class="border-slate-100 dark:border-slate-700">

                    <!-- Keluhan Section (CRITICAL FOR IDOR TEST ASSERTION) -->
                    <div class="space-y-1.5">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Keluhan Utama</span>
                        <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/80">
                            <p class="text-slate-700 dark:text-slate-300 text-xs italic font-medium">
                                "{{ $queue->complaint ?? 'Tidak ada keluhan tertulis.' }}"
                            </p>
                        </div>
                    </div>

                    <!-- Barcode Mockup for Premium Ticket Aesthetics -->
                    <div class="pt-4 flex flex-col items-center justify-center space-y-2 opacity-80">
                        <div class="flex items-center space-x-0.5">
                            @php $barcodeWidths = ['w-px','w-0.5','w-1','w-px','w-0.5','w-1','w-px','w-0.5','w-px','w-1','w-0.5','w-px','w-1','w-px','w-0.5','w-1','w-px','w-0.5','w-px','w-1','w-0.5','w-px','w-1','w-px']; @endphp
                            @foreach ($barcodeWidths as $bw)
                                <div class="bg-slate-800 dark:bg-slate-200 h-8 {{ $bw }}"></div>
                            @endforeach
                        </div>
                        <span class="text-[9px] font-mono text-slate-400 dark:text-slate-500 uppercase tracking-widest">KMS-ANT-{{ str_pad($queue->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>

                </div>
            </div>

            <!-- Ticket Action Buttons -->
            <div class="flex flex-col gap-3 px-2 sm:px-0">
                <a href="{{ route('patients.queues.index') }}" class="w-full inline-flex items-center justify-center px-5 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Antrean Saya
                </a>

                @if ($queue->status !== 'batal' && $queue->status !== 'selesai')
                    <!-- Cancellation Button Form (targets destroy route with DELETE) -->
                    <form action="{{ route('patients.queues.destroy', $queue->id) }}" method="POST" class="w-full" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan antrean ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3.5 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900 text-rose-600 dark:text-rose-400 font-bold rounded-2xl transition text-sm border border-rose-200/40 dark:border-rose-900/30 shadow-sm">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Batalkan Antrean
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
