<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Portal Pasien - Klinik Mitra Sehat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{--
                Kebutuhan Praktikum (Broken Access Control Simulation):
                Halaman ini dapat diakses oleh semua user yang sudah login, termasuk user dengan
                role 'petugas', karena RoleMiddleware tidak memvalidasi role sesungguhnya.
                Seorang pasien dapat mengetik /petugas/dashboard di URL bar untuk mengakses
                portal petugas medis tanpa hambatan (Missing Function Level Access Control).
            --}}

            <!-- Welcome Header Card -->
            <div class="bg-gradient-to-r from-emerald-500 via-teal-600 to-cyan-600 rounded-2xl shadow-xl overflow-hidden text-white relative">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-black/10 rounded-full blur-2xl"></div>
                
                <div class="p-8 md:p-12 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold mt-3 tracking-tight">
                            Selamat Datang, {{ Auth::user()->name }}!
                        </h1>
                        <p class="text-emerald-100/90 mt-2 text-base max-w-xl">
                            Akses rekam medis, antrean dokter, dan informasi resep obat Anda dengan mudah dan cepat melalui portal pasien terintegrasi.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="#appointment" class="px-5 py-3 bg-white text-teal-800 font-bold rounded-xl shadow-md hover:bg-teal-50 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                            Daftar Antrean
                        </a>
                        <a href="#history" class="px-5 py-3 bg-teal-700/50 backdrop-blur-md text-white font-bold border border-teal-400/30 rounded-xl hover:bg-teal-700/70 transition duration-150 ease-in-out">
                            Rekam Medis
                        </a>
                    </div>
                </div>
            </div>

            <!-- Patient Quick Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Info 1: Active Appointment -->
                <div id="appointment" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-950/50 rounded-xl text-emerald-600 dark:text-emerald-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 text-xs font-semibold rounded-full">
                            Aktif
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Antrean Aktif Anda</h3>
                    <div class="mt-4 space-y-2">
                        <p class="text-sm text-slate-600 dark:text-slate-400 flex justify-between">
                            <span>Poli / Dokter:</span>
                            <strong class="text-slate-800 dark:text-slate-200">Poli Umum / dr. Budiman</strong>
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 flex justify-between">
                            <span>Nomor Antrean:</span>
                            <strong class="text-emerald-600 dark:text-emerald-400 text-lg">A-08</strong>
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 flex justify-between">
                            <span>Estimasi Dilayani:</span>
                            <strong class="text-slate-800 dark:text-slate-200">14:45 WIB</strong>
                        </p>
                    </div>
                </div>

                <!-- Info 2: Medical Record Summary -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-950/50 rounded-xl text-blue-600 dark:text-blue-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="px-2.5 py-0.5 bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 text-xs font-semibold rounded-full">
                            Tersedia
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Rekam Medis Terakhir</h3>
                    <div class="mt-4 space-y-2">
                        <p class="text-sm text-slate-600 dark:text-slate-400 flex justify-between">
                            <span>Tanggal Kunjungan:</span>
                            <strong class="text-slate-800 dark:text-slate-200">22 Mei 2026</strong>
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 flex justify-between">
                            <span>Diagnosa:</span>
                            <strong class="text-slate-800 dark:text-slate-200">Hipertensi Essential</strong>
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 flex justify-between">
                            <span>Terapi / Resep:</span>
                            <strong class="text-slate-800 dark:text-slate-200">Amlodipine 5mg (1x1)</strong>
                        </p>
                    </div>
                </div>

                <!-- Info 3: Billing Info -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-950/50 rounded-xl text-purple-600 dark:text-purple-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="px-2.5 py-0.5 bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 text-xs font-semibold rounded-full">
                            Belum Bayar
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Tagihan Layanan</h3>
                    <div class="mt-4 space-y-2">
                        <p class="text-sm text-slate-600 dark:text-slate-400 flex justify-between">
                            <span>No. Invoice:</span>
                            <strong class="text-slate-800 dark:text-slate-200">#INV-2026052201</strong>
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 flex justify-between">
                            <span>Total Biaya:</span>
                            <strong class="text-slate-800 dark:text-slate-200">Rp 175.000</strong>
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 flex justify-between">
                            <span>Metode Pembayaran:</span>
                            <strong class="text-purple-600 dark:text-purple-400 font-semibold">Transfer Virtual Account</strong>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Medical History Table Section -->
            <div id="history" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Riwayat Kunjungan & Rekam Medis</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Daftar rekam medis lengkap Anda yang tercatat di Klinik Mitra Sehat</p>
                    </div>
                    <button class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Unduh Semua Riwayat
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/30 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700/50">
                                <th class="p-4 pl-6">Tanggal</th>
                                <th class="p-4">Dokter Pemeriksa</th>
                                <th class="p-4">Keluhan Utama</th>
                                <th class="p-4">Diagnosa Medis</th>
                                <th class="p-4">Rekomendasi Terapi</th>
                                <th class="p-4 pr-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-sm text-slate-600 dark:text-slate-300">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                                <td class="p-4 pl-6 font-medium text-slate-900 dark:text-slate-100">22 Mei 2026</td>
                                <td class="p-4 flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                        BD
                                    </div>
                                    <span>dr. Budiman (Sp.PD)</span>
                                </td>
                                <td class="p-4">Sering pusing di tengkuk belakang, leher kaku</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-xs font-semibold rounded-md">
                                        Hipertensi Essential
                                    </span>
                                </td>
                                <td class="p-4">Amlodipine 5mg (1x1 malam), kurangi garam berlebih</td>
                                <td class="p-4 pr-6 text-center">
                                    <a href="#" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Detail</a>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                                <td class="p-4 pl-6 font-medium text-slate-900 dark:text-slate-100">10 April 2026</td>
                                <td class="p-4 flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs">
                                        AN
                                    </div>
                                    <span>dr. Anita Nastasya</span>
                                </td>
                                <td class="p-4">Demam tinggi naik turun 3 hari, lemas, nyeri sendi</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 bg-teal-100 dark:bg-teal-950 text-teal-800 dark:text-teal-300 text-xs font-semibold rounded-md">
                                        Observasi Febris / Susp. Dengue
                                    </span>
                                </td>
                                <td class="p-4">Paracetamol 500mg (3x1 k/p), cek darah rutin besok pagi</td>
                                <td class="p-4 pr-6 text-center">
                                    <a href="#" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Detail</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
