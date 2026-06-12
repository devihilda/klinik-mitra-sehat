<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Tambah Dokter Baru') }}
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
                <span class="text-purple-600 dark:text-purple-400 font-semibold">Tambah Dokter</span>
            </nav>

            <!-- Form Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-purple-600 to-cyan-600 p-6 flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Tambah Dokter Baru</h3>
                        <p class="text-purple-100/80 text-sm">Lengkapi data dokter di bawah ini</p>
                    </div>
                </div>

                <!-- Form Body -->
                <form action="{{ route('doctors.store') }}" method="POST" class="p-6 md:p-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Dokter -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Dokter <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: dr. Budiman, Sp.PD" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 dark:focus:border-purple-400 transition" />
                            @error('name')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor SIP -->
                        <div>
                            <label for="sip" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nomor SIP <span class="text-rose-500">*</span></label>
                            <input type="text" name="sip" id="sip" value="{{ old('sip') }}" required placeholder="Contoh: SIP/123/2026" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 dark:focus:border-purple-400 transition" />
                            @error('sip')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Spesialisasi -->
                        <div>
                            <label for="specialization" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Spesialisasi <span class="text-rose-500">*</span></label>
                            <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}" required placeholder="Contoh: Dokter Umum, Sp. Anak" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 dark:focus:border-purple-400 transition" />
                            @error('specialization')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Poliklinik -->
                        <div>
                            <label for="polyclinic_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Poliklinik <span class="text-rose-500">*</span></label>
                            <select name="polyclinic_id" id="polyclinic_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 dark:focus:border-purple-400 transition">
                                <option value="">— Pilih Poliklinik —</option>
                                @foreach ($polyclinics as $polyclinic)
                                    <option value="{{ $polyclinic->id }}" {{ old('polyclinic_id') == $polyclinic->id ? 'selected' : '' }}>{{ $polyclinic->name }}</option>
                                @endforeach
                            </select>
                            @error('polyclinic_id')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. HP -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">No. HP <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="Contoh: 081234567890" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 dark:focus:border-purple-400 transition" />
                            @error('phone')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Status <span class="text-rose-500">*</span></label>
                            <select name="status" id="status" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 dark:focus:border-purple-400 transition">
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="cuti" {{ old('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="{{ route('doctors.index') }}" class="px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold rounded-xl shadow-md hover:from-purple-700 hover:to-cyan-700 transition transform hover:-translate-y-0.5 text-sm">
                            <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Dokter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
