<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Manajemen Jadwal Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Header Bar: Title + Add Button -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Jadwal Praktik Dokter</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola jadwal bertugas dan alokasi kuota dokter di Klinik Mitra Sehat</p>
                </div>
                <a href="{{ route('doctor-schedules.create') }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl shadow-md hover:from-amber-600 hover:to-orange-700 transition duration-150 ease-in-out transform hover:-translate-y-0.5 shadow-amber-500/20">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jadwal Baru
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

            <!-- Table Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                @if ($schedules->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900/30 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700/50">
                                    <th class="p-4 pl-6">No</th>
                                    <th class="p-4">Dokter & Spesialisasi</th>
                                    <th class="p-4">Poli</th>
                                    <th class="p-4">Hari & Waktu</th>
                                    <th class="p-4 text-center">Kuota</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4 pr-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-sm text-slate-600 dark:text-slate-300">
                                @foreach ($schedules as $index => $schedule)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                                        <td class="p-4 pl-6 font-medium text-slate-400 dark:text-slate-500">{{ $index + 1 }}</td>
                                        <td class="p-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                                    {{ strtoupper(substr($schedule->doctor->name ?? 'DR', 0, 2)) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $schedule->doctor->name ?? '-' }}</span>
                                                    <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">{{ $schedule->doctor->specialization ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $schedule->polyclinic->name ?? '-' }}</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-100 dark:border-amber-900/30">
                                                    {{ $schedule->day }}
                                                </span>
                                                <span class="inline-flex items-center text-xs font-mono text-slate-500 dark:text-slate-400">
                                                    <svg class="h-3.5 w-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                                                </span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center font-bold text-slate-800 dark:text-slate-200">
                                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded-md text-xs">{{ $schedule->quota }} Pasien</span>
                                        </td>
                                        <td class="p-4">
                                            @if ($schedule->is_active)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-900/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 pr-6">
                                            <div class="flex items-center justify-center space-x-2">
                                                <!-- Detail -->
                                                <a href="{{ route('doctor-schedules.show', $schedule->id) }}" class="p-2 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900 transition" title="Lihat Detail">
                                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <!-- Edit -->
                                                <a href="{{ route('doctor-schedules.edit', $schedule->id) }}" class="p-2 bg-orange-50 dark:bg-orange-950/50 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900 transition" title="Edit Jadwal">
                                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <!-- Hapus -->
                                                <form action="{{ route('doctor-schedules.destroy', $schedule->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal dokter ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900 transition" title="Hapus Jadwal">
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
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 rounded-3xl flex items-center justify-center mb-6 shadow-inner">
                            <svg class="h-12 w-12 text-amber-600 dark:text-amber-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-700 dark:text-slate-200">Belum Ada Jadwal Dokter</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-sm mx-auto">
                            Belum ada jadwal bertugas dokter yang ditambahkan ke sistem. Mulai tambahkan jadwal baru dengan menekan tombol di bawah.
                        </p>
                        <a href="{{ route('doctor-schedules.create') }}" class="inline-flex items-center mt-6 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl shadow-md hover:from-amber-600 hover:to-orange-700 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Jadwal Baru
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
