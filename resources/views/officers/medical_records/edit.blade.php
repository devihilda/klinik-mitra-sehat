<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('officers.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Portal Petugas</a>
            <span>/</span>
            <a href="{{ route('medical-records.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Rekam Medis</a>
            <span>/</span>
            <span class="font-semibold text-slate-800 dark:text-slate-100">Edit Rekam Medis</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Card Header -->
                <div class="p-6 border-b border-slate-100 dark:border-slate-700/50 bg-gradient-to-r from-amber-500 to-orange-500">
                    <h3 class="text-xl font-bold text-white">Edit Catatan Rekam Medis</h3>
                    <p class="text-amber-100/80 text-sm mt-1">Perbarui informasi pemeriksaan medis untuk pasien <strong>{{ $record->patient->user->name ?? '-' }}</strong></p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('medical-records.update', $record->id) }}" class="p-6 md:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pilih Pasien -->
                        <div>
                            <x-input-label for="patient_id" value="Pilih Pasien" />
                            <select id="patient_id" name="patient_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Cari / Pilih Pasien --</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $record->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->user->name ?? '-' }} (ID: #{{ $patient->id }}) - {{ $patient->phone }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        </div>

                        <!-- Tanggal Kunjungan -->
                        <div>
                            <x-input-label for="visit_date" value="Tanggal Kunjungan" />
                            <x-text-input id="visit_date" name="visit_date" type="date" class="mt-1 block w-full" :value="old('visit_date', $record->visit_date)" required />
                            <x-input-error :messages="$errors->get('visit_date')" class="mt-2" />
                        </div>

                        <!-- Diagnosis -->
                        <div class="md:col-span-2">
                            <x-input-label for="diagnosis" value="Diagnosis Pemeriksaan" />
                            <textarea id="diagnosis" name="diagnosis" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('diagnosis', $record->diagnosis) }}</textarea>
                            <x-input-error :messages="$errors->get('diagnosis')" class="mt-2" />
                        </div>

                        <!-- Tindakan / Resep / Terapi -->
                        <div class="md:col-span-2">
                            <x-input-label for="treatment" value="Tindakan / Terapi / Obat-obatan" />
                            <textarea id="treatment" name="treatment" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('treatment', $record->treatment) }}</textarea>
                            <x-input-error :messages="$errors->get('treatment')" class="mt-2" />
                        </div>

                        <!-- Catatan Tambahan (Optional) -->
                        <div class="md:col-span-2">
                            <x-input-label for="notes" value="Catatan Tambahan (Opsional)" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes', $record->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="{{ route('medical-records.index') }}" class="px-5 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl shadow-md hover:from-amber-600 hover:to-orange-600 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                            Update Rekam Medis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
