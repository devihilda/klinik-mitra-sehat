<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Manajemen Data Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Header Bar: Title + Add Button -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Daftar Dokter</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola seluruh data dokter yang terdaftar di Klinik Mitra Sehat</p>
                </div>
                <a href="{{ route('doctors.create') }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold rounded-xl shadow-md hover:from-purple-700 hover:to-cyan-700 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Dokter Baru
                </a>
            </div>

            <!-- Search Bar (Vulnerable to SQL Injection) -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-4">
                <form action="{{ route('doctors.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama dokter..." class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 dark:focus:border-purple-400 transition" />
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-5 py-3 bg-purple-600 text-white font-semibold rounded-xl hover:bg-purple-700 transition text-sm">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('doctors.index') }}" class="inline-flex items-center px-4 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
                @if(request('search'))
                    <div class="mt-3 flex items-center space-x-2">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Hasil pencarian untuk:</span>
                        <span class="text-xs font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-950/50 px-2.5 py-1 rounded-lg">{{ request('search') }}</span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">— {{ $doctors->count() }} dokter ditemukan</span>
                    </div>
                @endif
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

            <!-- Doctors Table Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                @if ($doctors->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900/30 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700/50">
                                    <th class="p-4 pl-6">No</th>
                                    <th class="p-4">Nama & Spesialisasi</th>
                                    <th class="p-4">No. SIP</th>
                                    <th class="p-4">Poli</th>
                                    <th class="p-4">No. HP</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4 pr-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-sm text-slate-600 dark:text-slate-300">
                                @foreach ($doctors as $index => $doctor)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                                        <td class="p-4 pl-6 font-medium text-slate-400 dark:text-slate-500">{{ $index + 1 }}</td>
                                        <td class="p-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-cyan-400 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                                    {{ strtoupper(substr($doctor->name, 0, 2)) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $doctor->name }}</span>
                                                    <span class="text-xs text-purple-500 dark:text-purple-400">{{ $doctor->specialization }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="font-mono text-xs bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 px-2 py-1 rounded-md">{{ $doctor->sip }}</span>
                                        </td>
                                        <td class="p-4">
                                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $doctor->polyclinic->name ?? '-' }}</span>
                                        </td>
                                        <td class="p-4">{{ $doctor->phone }}</td>
                                        <td class="p-4">
                                            @php
                                                $statusColors = [
                                                    'aktif' => 'bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300',
                                                    'cuti' => 'bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300',
                                                    'tidak aktif' => 'bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300',
                                                ];
                                                $statusIcons = [
                                                    'aktif' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                                    'cuti' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                                    'tidak aktif' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$doctor->status] ?? 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400' }}">
                                                <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $statusIcons[$doctor->status] ?? '' }}" />
                                                </svg>
                                                {{ ucfirst($doctor->status) }}
                                            </span>
                                        </td>
                                        <td class="p-4 pr-6">
                                            <div class="flex items-center justify-center space-x-2">
                                                <!-- Detail -->
                                                <a href="{{ route('doctors.show', $doctor->id) }}" class="p-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition" title="Lihat Detail">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <!-- Edit -->
                                                <a href="{{ route('doctors.edit', $doctor->id) }}" class="p-2 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900 transition" title="Edit Data">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <!-- Hapus -->
                                                <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dokter ini?')">
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
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-purple-100 to-cyan-100 dark:from-purple-900/30 dark:to-cyan-900/30 rounded-2xl flex items-center justify-center mb-5 shadow-inner">
                            <svg class="h-10 w-10 text-purple-500 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-700 dark:text-slate-200">
                            @if(request('search'))
                                Dokter Tidak Ditemukan
                            @else
                                Belum Ada Data Dokter
                            @endif
                        </h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-sm mx-auto">
                            @if(request('search'))
                                Tidak ditemukan dokter dengan kata kunci "{{ request('search') }}". Coba kata kunci lain atau reset pencarian.
                            @else
                                Belum ada dokter yang terdaftar di sistem. Klik tombol di bawah untuk menambahkan dokter pertama.
                            @endif
                        </p>
                        @if(!request('search'))
                            <a href="{{ route('doctors.create') }}" class="inline-flex items-center mt-6 px-5 py-3 bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold rounded-xl shadow-md hover:from-purple-700 hover:to-cyan-700 transition">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Dokter Baru
                            </a>
                        @else
                            <a href="{{ route('doctors.index') }}" class="inline-flex items-center mt-6 px-5 py-3 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-xl hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset Pencarian
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
