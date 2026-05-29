<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Portal Petugas Medis - Klinik Mitra Sehat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{--
                Kebutuhan Praktikum (Broken Access Control Simulation):
                Halaman ini seharusnya HANYA dapat diakses oleh akun dengan role 'petugas'.
                Namun karena celah Missing Function Level Access Control (Opsi 1) aktif di
                RoleMiddleware.php, pengguna mana saja yang telah login (termasuk Pasien)
                dapat langsung memintas proteksi rute dan melihat seluruh data rekam medis
                serta mengontrol antrean di halaman ini.
            --}}

            <!-- Welcome Officer Header Card -->
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 rounded-2xl shadow-xl overflow-hidden text-white relative">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-black/10 rounded-full blur-2xl"></div>
                
                <div class="p-8 md:p-12 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold mt-3 tracking-tight">
                            Panel Operator Klinik, {{ Auth::user()->name }}!
                        </h1>
                        <p class="text-blue-100/90 mt-2 text-base max-w-xl">
                            Kelola pendaftaran antrean pasien, input rekam medis baru, verifikasi tagihan, serta kelola jadwal praktikum dokter secara real-time.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button class="px-5 py-3 bg-white text-blue-800 font-bold rounded-xl shadow-md hover:bg-blue-50 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                            + Pendaftaran Baru
                        </button>
                        <button class="px-5 py-3 bg-blue-700/50 backdrop-blur-md text-white font-bold border border-blue-400/30 rounded-xl hover:bg-blue-700/70 transition duration-150 ease-in-out">
                            Cetak Laporan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Officer Stats Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1: Active Patients -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Total Pasien Terdaftar</span>
                        <span class="p-2 bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 rounded-lg">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-3xl font-black text-slate-800 dark:text-slate-100">142</h3>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-2 flex items-center font-bold">
                            <span>↑ 12% dari bulan lalu</span>
                        </p>
                    </div>
                </div>

                <!-- Card 2: Queues today -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Antrean Hari Ini</span>
                        <span class="p-2 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 rounded-lg">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-3xl font-black text-slate-800 dark:text-slate-100">28</h3>
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-2 flex items-center font-bold">
                            <span>8 antrean dalam antrian</span>
                        </p>
                    </div>
                </div>

                <!-- Card 3: Doctors Duty -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Dokter Aktif</span>
                        <span class="p-2 bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 rounded-lg">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-3xl font-black text-slate-800 dark:text-slate-100">4</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex items-center font-semibold">
                            <span>Poli Umum, Anak, Gigi, & Dalam</span>
                        </p>
                    </div>
                </div>

                <!-- Card 4: Pending Reports -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Verifikasi Tagihan</span>
                        <span class="p-2 bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400 rounded-lg">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-3xl font-black text-rose-600 dark:text-rose-400">3</h3>
                        <p class="text-xs text-rose-500 dark:text-rose-400 mt-2 flex items-center font-bold">
                            <span>Butuh persetujuan manual</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Control Panel Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: List of Active Appointments -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden lg:col-span-2">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Antrean Aktif Saat Ini</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Monitoring real-time antrean pasien per poliklinik</p>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-full">
                            Live Update
                        </span>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <!-- Appointment 1 -->
                        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 flex flex-col items-center justify-center font-black">
                                    <span class="text-xs text-blue-500">Antrean</span>
                                    <span class="text-base leading-none">A-08</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-slate-100">Hilda Deviana</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Poli Umum • dr. Budiman • BPJS Kesehatan</p>
                                    <span class="inline-block mt-2 px-2 py-0.5 bg-yellow-100 dark:bg-yellow-950 text-yellow-800 dark:text-yellow-300 text-xs font-semibold rounded-md">
                                        Menunggu Pemeriksaan
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                                    Panggil
                                </button>
                                <button class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                    Lewati
                                </button>
                            </div>
                        </div>

                        <!-- Appointment 2 -->
                        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex flex-col items-center justify-center font-black">
                                    <span class="text-xs text-purple-500">Antrean</span>
                                    <span class="text-base leading-none">C-02</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-slate-100">Rizki Ramadhan</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Poli Gigi • drg. Haryanto • Mandiri Inhealth</p>
                                    <span class="inline-block mt-2 px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 text-xs font-semibold rounded-md">
                                        Sedang Dilayani
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition">
                                    Selesai
                                </button>
                                <button class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                    Rujuk
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Quick Actions Panel -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Aksi Cepat Administrasi</h3>
                    
                    <div class="space-y-3">
                        <button class="w-full p-4 bg-slate-50 dark:bg-slate-900/30 hover:bg-blue-50 dark:hover:bg-blue-950/20 border border-slate-100 dark:border-slate-700 text-left rounded-xl flex items-center space-x-4 transition">
                            <span class="p-3 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">Registrasi Pasien Baru</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Pendaftaran manual langsung dari resepsionis</p>
                            </div>
                        </button>

                        <button class="w-full p-4 bg-slate-50 dark:bg-slate-900/30 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 border border-slate-100 dark:border-slate-700 text-left rounded-xl flex items-center space-x-4 transition">
                            <span class="p-3 bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-300 rounded-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Rekam Medis</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Tulis rekam medis, keluhan & resep obat pasien</p>
                            </div>
                        </button>

                        <button class="w-full p-4 bg-slate-50 dark:bg-slate-900/30 hover:bg-purple-50 dark:hover:bg-purple-950/20 border border-slate-100 dark:border-slate-700 text-left rounded-xl flex items-center space-x-4 transition">
                            <span class="p-3 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300 rounded-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m0 0a2 2 0 01-2 2m2-2a2 2 0 00-2-2m2 2a2 2 0 012 2m0 0a2 2 0 01-2 2m2-2a2 2 0 00-2-2m-9 9h.01M8 12a4 4 0 118 0 4 4 0 01-8 0zm-4 6v-3a4 4 0 018 0v3H4z" />
                                </svg>
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">Atur Jadwal Dokter</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Pembaruan jadwal praktek dan kuota antrean</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
