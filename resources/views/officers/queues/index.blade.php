<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Manajemen Antrean') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Header Bar: Title + Add Button -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Daftar Antrean Klinik</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Pantau dan kelola seluruh antrean pemeriksaan pasien di Klinik Mitra Sehat</p>
                </div>
                <a href="{{ route('queues.create') }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-violet-500 to-fuchsia-600 text-white font-bold rounded-xl shadow-md hover:from-violet-600 hover:to-fuchsia-700 transition duration-150 ease-in-out transform hover:-translate-y-0.5 shadow-violet-500/20">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Daftarkan Antrean Baru
                </a>
            </div>

            <!-- Flash Message -->
            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 p-4 rounded-xl flex items-center space-x-3 shadow-sm transition-all duration-300">
                    <div class="p-1.5 bg-emerald-100 dark:bg-emerald-900 rounded-lg text-emerald-600 dark:text-emerald-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filter Panel -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6">
                <form method="GET" action="{{ route('queues.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="queue_date" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tanggal Antrean</label>
                        <input type="date" name="queue_date" id="queue_date" value="{{ request('queue_date') }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:border-violet-500 focus:ring focus:ring-violet-200 dark:focus:ring-violet-900 transition">
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Status Antrean</label>
                        <select name="status" id="status" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:border-violet-500 focus:ring focus:ring-violet-200 dark:focus:ring-violet-900 transition">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diperiksa" {{ request('status') == 'diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-5 py-3 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl transition duration-150 shadow-md shadow-violet-500/10">
                            Filter
                        </button>
                        @if (request()->filled('queue_date') || request()->filled('status'))
                            <a href="{{ route('queues.index') }}" class="px-5 py-3 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition text-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                @if ($queues->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900/30 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700/50">
                                    <th class="p-4 pl-6 text-center">No. Antrean</th>
                                    <th class="p-4">Pasien</th>
                                    <th class="p-4">Poliklinik & Dokter</th>
                                    <th class="p-4">Tanggal Antrean</th>
                                    <th class="p-4">Keluhan</th>
                                    <th class="p-4 text-center">Status</th>
                                    <th class="p-4 pr-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-sm text-slate-600 dark:text-slate-300">
                                @foreach ($queues as $queue)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                                        <td class="p-4 pl-6 text-center">
                                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 font-black text-lg border border-violet-200 dark:border-violet-800/40">
                                                {{ str_pad($queue->queue_number, 2, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $queue->patient->user->name ?? '-' }}</span>
                                                <span class="text-xs text-slate-400 font-mono">{{ $queue->patient->phone ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $queue->polyclinic->name ?? '-' }}</span>
                                                <span class="text-xs text-violet-600 dark:text-violet-400 font-medium">{{ $queue->doctor->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center text-xs font-mono font-bold text-slate-700 dark:text-slate-300 px-2 py-1 rounded bg-slate-100 dark:bg-slate-700">
                                                {{ \Carbon\Carbon::parse($queue->queue_date)->translatedFormat('d M Y') }}
                                            </span>
                                        </td>
                                        <td class="p-4 max-w-xs truncate" title="{{ $queue->complaint }}">
                                            <span class="text-slate-600 dark:text-slate-300">{{ $queue->complaint ?? '-' }}</span>
                                        </td>
                                        <td class="p-4 text-center">
                                            @if ($queue->status === 'menunggu')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-200/50 dark:border-amber-900/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                                    Menunggu
                                                </span>
                                            @elseif ($queue->status === 'diperiksa')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-800 dark:text-blue-300 border border-blue-200/50 dark:border-blue-900/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span>
                                                    Diperiksa
                                                </span>
                                            @elseif ($queue->status === 'selesai')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-900/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300 border border-rose-200/50 dark:border-rose-900/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                                    Batal
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 pr-6">
                                            <div class="flex items-center justify-center space-x-2">
                                                <!-- Detail -->
                                                <a href="{{ route('queues.show', $queue->id) }}" class="p-2 bg-violet-50 dark:bg-violet-950/50 text-violet-600 dark:text-violet-400 rounded-lg hover:bg-violet-100 dark:hover:bg-violet-900 transition" title="Lihat Detail">
                                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <!-- Edit -->
                                                <a href="{{ route('queues.edit', $queue->id) }}" class="p-2 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900 transition" title="Edit Antrean">
                                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <!-- Hapus -->
                                                <form action="{{ route('queues.destroy', $queue->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus antrean ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900 transition" title="Hapus Antrean">
                                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                    <div class="p-16 text-center">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-violet-100 to-fuchsia-100 dark:from-violet-900/30 dark:to-fuchsia-900/30 rounded-3xl flex items-center justify-center mb-6 shadow-inner animate-pulse">
                            <svg class="h-12 w-12 text-violet-600 dark:text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3m-3-4.5h5.25m3-.75H18a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 19.5v-9A2.25 2.25 0 016 8.25h3.75a1.125 1.125 0 01.75.3M10.5 2.25H12a2.25 2.25 0 012.25 2.25v2.25H10.5V2.25z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-700 dark:text-slate-200">Belum Ada Antrean Pasien</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-sm mx-auto">
                            Saat ini belum ada pendaftaran antrean pasien yang tercatat untuk tanggal atau filter yang dipilih.
                        </p>
                        <a href="{{ route('queues.create') }}" class="inline-flex items-center mt-6 px-6 py-3 bg-gradient-to-r from-violet-500 to-fuchsia-600 text-white font-bold rounded-xl shadow-md hover:from-violet-600 hover:to-fuchsia-700 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Daftarkan Antrean Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
