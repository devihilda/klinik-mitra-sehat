<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('officers.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Portal Petugas</a>
            <span>/</span>
            <a href="{{ route('patients.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Manajemen Pasien</a>
            <span>/</span>
            <span class="font-semibold text-slate-800 dark:text-slate-100">Detail Pasien</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Patient Profile Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Header Banner -->
                <div class="p-6 md:p-8 bg-gradient-to-r from-blue-600 to-indigo-600 flex flex-col md:flex-row md:items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-2xl font-black">
                        {{ strtoupper(substr($patient->user->name ?? '?', 0, 2)) }}
                    </div>
                    <div class="text-white">
                        <h3 class="text-2xl font-extrabold tracking-tight">{{ $patient->user->name ?? '-' }}</h3>
                        <p class="text-blue-100/80 mt-1">{{ $patient->user->email ?? '-' }}</p>
                        <div class="flex items-center space-x-3 mt-3">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold">
                                {{ ucfirst($patient->gender) }}
                            </span>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold">
                                ID Pasien: #{{ $patient->id }}
                            </span>
                            {{-- Kebutuhan Praktikum (IDOR): Menampilkan user_id secara eksplisit agar attacker bisa mengenumerasi ID --}}
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold">
                                User ID: {{ $patient->user_id }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Detail Grid -->
                <div class="p-6 md:p-8">
                    <h4 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-6">Informasi Pribadi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <!-- Nama -->
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Nama Lengkap</span>
                            <span class="text-base font-semibold text-slate-800 dark:text-slate-100">{{ $patient->user->name ?? '-' }}</span>
                        </div>
                        <!-- Email -->
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Alamat Email</span>
                            <span class="text-base font-semibold text-slate-800 dark:text-slate-100">{{ $patient->user->email ?? '-' }}</span>
                        </div>
                        <!-- No. HP -->
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Nomor HP / WhatsApp</span>
                            <span class="text-base font-semibold text-slate-800 dark:text-slate-100">{{ $patient->phone }}</span>
                        </div>
                        <!-- Jenis Kelamin -->
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Jenis Kelamin</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold inline-block
                                {{ $patient->gender === 'laki-laki' ? 'bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300' : 'bg-pink-100 dark:bg-pink-950 text-pink-800 dark:text-pink-300' }}">
                                {{ ucfirst($patient->gender) }}
                            </span>
                        </div>
                        <!-- Tanggal Lahir -->
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Tanggal Lahir</span>
                            <span class="text-base font-semibold text-slate-800 dark:text-slate-100">{{ \Carbon\Carbon::parse($patient->birth_date)->translatedFormat('d F Y') }}</span>
                        </div>
                        <!-- Role -->
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Role Akun</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold inline-block bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300">
                                {{ ucfirst($patient->user->role ?? '-') }}
                            </span>
                        </div>
                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Alamat Lengkap</span>
                            <span class="text-base font-semibold text-slate-800 dark:text-slate-100">{{ $patient->address }}</span>
                        </div>
                        <!-- Tanggal Daftar -->
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Terdaftar Pada</span>
                            <span class="text-base font-semibold text-slate-800 dark:text-slate-100">{{ $patient->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="px-6 md:px-8 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/20 flex items-center justify-between">
                    <a href="{{ route('patients.index') }}" class="px-5 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        ← Kembali ke Daftar
                    </a>
                    <a href="{{ route('patients.edit', $patient->id) }}" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl shadow-md hover:from-amber-600 hover:to-orange-600 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                        Edit Data Pasien
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
