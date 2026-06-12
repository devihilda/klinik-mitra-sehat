<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Daftar Antrean Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
                <a href="{{ route('dashboard') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Portal Pasien</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <a href="{{ route('patients.queues.index') }}" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Antrean Saya</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <span class="text-violet-600 dark:text-violet-400 font-semibold">Pendaftaran Mandiri</span>
            </nav>

            <!-- Form Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-violet-500 to-fuchsia-600 p-6 flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Formulir Pendaftaran Antrean</h3>
                        <p class="text-violet-100/80 text-sm">Pilih dokter dan tentukan tanggal kunjungan Anda</p>
                    </div>
                </div>

                <!-- Form Body -->
                <form action="{{ route('patients.queues.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                    @csrf

                    <!-- Profile Validation Info -->
                    @error('patient')
                        <div class="bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 p-4 rounded-xl flex items-center space-x-3 shadow-sm text-sm font-semibold text-rose-800 dark:text-rose-200">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Dokter & Jadwal -->
                    <div>
                        <label for="doctor_schedule_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Dokter & Jadwal Praktik <span class="text-rose-500">*</span></label>
                        <select name="doctor_schedule_id" id="doctor_schedule_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 dark:focus:border-violet-400 transition">
                            <option value="">— Pilih Dokter & Hari Praktik —</option>
                            @foreach ($schedules as $schedule)
                                <option value="{{ $schedule->id }}" {{ old('doctor_schedule_id') == $schedule->id ? 'selected' : '' }}>
                                    {{ $schedule->doctor->name ?? '-' }} ({{ $schedule->doctor->specialization ?? '-' }}) — {{ $schedule->polyclinic->name ?? '-' }} [{{ $schedule->day }}, {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB] (Kuota: {{ $schedule->quota }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400 mt-1">Pastikan Anda memilih jadwal yang sesuai dengan hari kedatangan Anda.</p>
                        @error('doctor_schedule_id')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Rencana Berkunjung -->
                    <div>
                        <label for="queue_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tanggal Kedatangan <span class="text-rose-500">*</span></label>
                        <input type="date" name="queue_date" id="queue_date" value="{{ old('queue_date') }}" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 dark:focus:border-violet-400 transition" />
                        <p class="text-xs text-slate-400 mt-1">Harap pilih tanggal kunjungan yang sesuai dengan hari bertugas dokter.</p>
                        @error('queue_date')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keluhan -->
                    <div>
                        <label for="complaint" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Keluhan Utama</label>
                        <textarea name="complaint" id="complaint" rows="4" placeholder="Tuliskan keluhan atau gejala awal yang Anda rasakan untuk membantu dokter melakukan diagnosa awal..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 dark:focus:border-violet-400 transition">{{ old('complaint') }}</textarea>
                        @error('complaint')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="bg-violet-50 dark:bg-violet-950/20 border border-violet-100 dark:border-violet-900/30 rounded-xl p-4 flex items-start space-x-3 text-slate-600 dark:text-slate-300 text-xs">
                        <svg class="w-5 h-5 text-violet-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <strong class="text-violet-800 dark:text-violet-300 font-semibold block mb-0.5">Informasi Kuota & Nomor Antrean:</strong>
                            Pendaftaran Anda akan diproses secara otomatis. Sistem akan menghasilkan Nomor Antrean berurutan secara otomatis berdasarkan kuota dokter yang tersedia. Jika kuota penuh pada tanggal yang Anda pilih, silakan pilih tanggal atau dokter lain.
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="{{ route('patients.queues.index') }}" class="px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-violet-500 to-fuchsia-600 text-white font-bold rounded-xl shadow-md hover:from-violet-600 hover:to-fuchsia-700 transition transform hover:-translate-y-0.5 text-sm">
                            <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Kirim Pendaftaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
