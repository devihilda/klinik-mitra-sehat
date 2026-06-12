<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Ubah Detail Antrean') }}
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
                <span class="text-violet-600 dark:text-violet-400 font-semibold">Ubah Antrean</span>
            </nav>

            <!-- Form Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-violet-500 to-fuchsia-600 p-6 flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Ubah Antrean (No. Antrean: {{ str_pad($queue->queue_number, 2, '0', STR_PAD_LEFT) }})</h3>
                        <p class="text-violet-100/80 text-sm">Perbarui informasi kunjungan, keluhan, atau status antrean pasien di bawah</p>
                    </div>
                </div>

                <!-- Form Body -->
                <form action="{{ route('queues.update', $queue->id) }}" method="POST" class="p-6 md:p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pasien -->
                        <div class="md:col-span-2">
                            <label for="patient_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pasien <span class="text-rose-500">*</span></label>
                            <select name="patient_id" id="patient_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 dark:focus:border-violet-400 transition">
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $queue->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->user->name ?? '-' }} (NIK: {{ $patient->nik ?? '-' }}) - {{ $patient->phone ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jadwal Dokter -->
                        <div class="md:col-span-2">
                            <label for="doctor_schedule_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jadwal Praktik Dokter <span class="text-rose-500">*</span></label>
                            <select name="doctor_schedule_id" id="doctor_schedule_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 dark:focus:border-violet-400 transition">
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('doctor_schedule_id', $queue->doctor_schedule_id) == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->doctor->name ?? '-' }} ({{ $schedule->doctor->specialization ?? '-' }}) — {{ $schedule->polyclinic->name ?? '-' }} [{{ $schedule->day }}, {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB] (Kuota: {{ $schedule->quota }})
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_schedule_id')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Kunjungan -->
                        <div>
                            <label for="queue_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tanggal Antrean <span class="text-rose-500">*</span></label>
                            <input type="date" name="queue_date" id="queue_date" value="{{ old('queue_date', $queue->queue_date) }}" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 dark:focus:border-violet-400 transition" />
                            @error('queue_date')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Antrean -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Status Antrean <span class="text-rose-500">*</span></label>
                            <select name="status" id="status" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 dark:focus:border-violet-400 transition">
                                <option value="menunggu" {{ old('status', $queue->status) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diperiksa" {{ old('status', $queue->status) == 'diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                                <option value="selesai" {{ old('status', $queue->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="batal" {{ old('status', $queue->status) == 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keluhan -->
                        <div class="md:col-span-2">
                            <label for="complaint" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Keluhan Utama</label>
                            <textarea name="complaint" id="complaint" rows="4" placeholder="Tuliskan keluhan atau gejala awal yang dirasakan pasien..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 dark:focus:border-violet-400 transition">{{ old('complaint', $queue->complaint) }}</textarea>
                            @error('complaint')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="{{ route('queues.index') }}" class="px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-violet-500 to-fuchsia-600 text-white font-bold rounded-xl shadow-md hover:from-violet-600 hover:to-fuchsia-700 transition transform hover:-translate-y-0.5 text-sm">
                            <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Perbarui Antrean
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
