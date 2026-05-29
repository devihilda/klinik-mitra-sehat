<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('officers.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Portal Petugas</a>
            <span>/</span>
            <a href="{{ route('patients.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Manajemen Pasien</a>
            <span>/</span>
            <span class="font-semibold text-slate-800 dark:text-slate-100">Edit Data Pasien</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Card Header -->
                <div class="p-6 border-b border-slate-100 dark:border-slate-700/50 bg-gradient-to-r from-amber-500 to-orange-500">
                    <h3 class="text-xl font-bold text-white">Edit Data Pasien</h3>
                    <p class="text-amber-100/80 text-sm mt-1">Perbarui informasi pasien <strong>{{ $patient->user->name ?? '-' }}</strong> (ID: #{{ $patient->id }})</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('patients.update', $patient->id) }}" class="p-6 md:p-8 space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Section: Informasi Akun -->
                    <div>
                        <h4 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 flex items-center justify-center mr-3 text-sm font-black">1</span>
                            Informasi Akun Login
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                            <!-- Nama Lengkap -->
                            <div class="md:col-span-2">
                                <x-input-label for="name" value="Nama Lengkap" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $patient->user->name)" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <!-- Email -->
                            <div>
                                <x-input-label for="email" value="Alamat Email" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $patient->user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <!-- Password -->
                            <div>
                                {{-- Kebutuhan Praktikum: Password disimpan sebagai plaintext, field dikosongkan agar opsional --}}
                                <x-input-label for="password" value="Password (Kosongkan jika tidak diubah)" />
                                <x-text-input id="password" name="password" type="text" class="mt-1 block w-full" placeholder="Isi jika ingin mengubah password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="border-slate-100 dark:border-slate-700/50" />

                    <!-- Section: Data Pasien -->
                    <div>
                        <h4 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mr-3 text-sm font-black">2</span>
                            Data Pribadi Pasien
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                            <!-- No. HP -->
                            <div>
                                <x-input-label for="phone" value="Nomor HP / WhatsApp" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $patient->phone)" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            <!-- Jenis Kelamin -->
                            <div>
                                <x-input-label for="gender" value="Jenis Kelamin" />
                                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="laki-laki" {{ old('gender', $patient->gender) === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="perempuan" {{ old('gender', $patient->gender) === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>
                            <!-- Tanggal Lahir -->
                            <div>
                                <x-input-label for="birth_date" value="Tanggal Lahir" />
                                <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $patient->birth_date)" required />
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                            </div>
                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <x-input-label for="address" value="Alamat Lengkap" />
                                <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('address', $patient->address) }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="{{ route('patients.index') }}" class="px-5 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl shadow-md hover:from-amber-600 hover:to-orange-600 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                            Update Data Pasien
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
