<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Edit Jadwal Dokter') }}
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
                <a href="{{ route('doctor-schedules.show', $schedule->id) }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition">Detail Jadwal</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <span class="text-amber-600 dark:text-amber-400 font-semibold">Edit Jadwal</span>
            </nav>

            <!-- Form Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-amber-500 to-orange-600 p-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Edit Jadwal Praktik</h3>
                            <p class="text-amber-100/80 text-sm">Sesuaikan jadwal tugas untuk {{ $schedule->doctor->name ?? 'Dokter' }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white font-mono text-xs rounded-full border border-white/10">
                        ID Jadwal: #{{ $schedule->id }}
                    </span>
                </div>

                <!-- Form Body -->
                <form action="{{ route('doctor-schedules.update', $schedule->id) }}" method="POST" class="p-6 md:p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dokter -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Dokter <span class="text-rose-500">*</span></label>
                            <select name="doctor_id" id="doctor_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 dark:focus:border-amber-400 transition">
                                <option value="">— Pilih Dokter —</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id', $schedule->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }} ({{ $doctor->specialization }})
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Poliklinik -->
                        <div>
                            <label for="polyclinic_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Poliklinik <span class="text-rose-500">*</span></label>
                            <select name="polyclinic_id" id="polyclinic_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 dark:focus:border-amber-400 transition">
                                <option value="">— Pilih Poliklinik —</option>
                                @foreach ($polyclinics as $polyclinic)
                                    <option value="{{ $polyclinic->id }}" {{ old('polyclinic_id', $schedule->polyclinic_id) == $polyclinic->id ? 'selected' : '' }}>
                                        {{ $polyclinic->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('polyclinic_id')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hari -->
                        <div>
                            <label for="day" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Hari Praktik <span class="text-rose-500">*</span></label>
                            <select name="day" id="day" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 dark:focus:border-amber-400 transition">
                                <option value="">— Pilih Hari —</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $dayName)
                                    <option value="{{ $dayName }}" {{ old('day', $schedule->day) == $dayName ? 'selected' : '' }}>{{ $dayName }}</option>
                                @endforeach
                            </select>
                            @error('day')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kuota Pasien -->
                        <div>
                            <label for="quota" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Kuota Pasien <span class="text-rose-500">*</span></label>
                            <input type="number" min="1" name="quota" id="quota" value="{{ old('quota', $schedule->quota) }}" required placeholder="Contoh: 20" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 dark:focus:border-amber-400 transition" />
                            @error('quota')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam Mulai -->
                        <div>
                            <label for="start_time" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jam Mulai <span class="text-rose-500">*</span></label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 dark:focus:border-amber-400 transition" />
                            @error('start_time')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam Selesai -->
                        <div>
                            <label for="end_time" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jam Selesai <span class="text-rose-500">*</span></label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 dark:focus:border-amber-400 transition" />
                            @error('end_time')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="{{ route('doctor-schedules.index') }}" class="px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl shadow-md hover:from-amber-600 hover:to-orange-700 transition transform hover:-translate-y-0.5 text-sm">
                            <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
