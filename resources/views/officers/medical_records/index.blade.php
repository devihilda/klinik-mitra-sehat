<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Manajemen Rekam Medis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Daftar Rekam Medis</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola riwayat medis, diagnosis, dan terapi pasien Klinik Mitra Sehat</p>
                </div>
                <a href="{{ route('medical-records.create') }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white font-bold rounded-xl shadow-md hover:from-violet-700 hover:to-fuchsia-700 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Rekam Medis
                </a>
            </div>

            <!-- Flash Message -->
            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 p-4 rounded-xl flex items-center space-x-3">
                    <div class="p-1 bg-emerald-100 dark:bg-emerald-900 rounded-lg text-emerald-600 dark:text-emerald-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Medical Records Table Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                @if ($records->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900/30 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700/50">
                                    <th class="p-4 pl-6">No</th>
                                    <th class="p-4">Nama Pasien</th>
                                    <th class="p-4">Tanggal Kunjungan</th>
                                    <th class="p-4">Diagnosis</th>
                                    <th class="p-4">Ditangani Oleh</th>
                                    <th class="p-4 pr-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-sm text-slate-600 dark:text-slate-300">
                                @foreach ($records as $index => $record)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                                        <td class="p-4 pl-6 font-medium text-slate-400 dark:text-slate-500">{{ $index + 1 }}</td>
                                        <td class="p-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-9 h-9 rounded-full bg-violet-100 dark:bg-violet-950 text-violet-700 dark:text-violet-300 flex items-center justify-center font-bold text-xs">
                                                    {{ strtoupper(substr($record->patient->user->name ?? '?', 0, 2)) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $record->patient->user->name ?? '-' }}</span>
                                                    <span class="text-xs text-slate-400 dark:text-slate-500">ID Pasien: #{{ $record->patient_id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 font-medium text-slate-700 dark:text-slate-300">
                                            {{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="p-4 max-w-xs">
                                            {{--
                                                Kebutuhan Praktikum (Stored XSS):
                                                Sengaja dirender mentah-mentah menggunakan {!! !!}
                                                agar script/HTML jahat yang disimpan penyerang dapat dieksekusi langsung
                                                oleh browser siapa pun yang membuka halaman daftar rekam medis.
                                            --}}
                                            <div class="truncate text-slate-800 dark:text-slate-100 prose dark:prose-invert">
                                                {!! $record->diagnosis !!}
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 rounded-lg">
                                                {{ $record->officer->name ?? 'Sistem' }}
                                            </span>
                                        </td>
                                        <td class="p-4 pr-6">
                                            <div class="flex items-center justify-center space-x-2">
                                                <!-- Detail -->
                                                <a href="{{ route('medical-records.show', $record->id) }}" class="p-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition" title="Lihat Detail">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <!-- Edit -->
                                                <a href="{{ route('medical-records.edit', $record->id) }}" class="p-2 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900 transition" title="Edit Data">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <!-- Hapus -->
                                                <form action="{{ route('medical-records.destroy', $record->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900 transition" title="Hapus">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="p-12 text-center">
                        <div class="mx-auto w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-700 dark:text-slate-200">Belum Ada Rekam Medis</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-sm mx-auto">Belum ada rekam medis pasien yang tercatat. Klik tombol di bawah untuk membuat rekam medis baru.</p>
                        <a href="{{ route('medical-records.create') }}" class="inline-flex items-center mt-6 px-5 py-3 bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white font-bold rounded-xl shadow-md hover:from-violet-700 hover:to-fuchsia-700 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Rekam Medis
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
