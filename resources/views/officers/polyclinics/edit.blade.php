<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('officers.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Portal Petugas</a>
            <span>/</span>
            <a href="{{ route('polyclinics.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Manajemen Poli</a>
            <span>/</span>
            <span class="font-semibold text-slate-800 dark:text-slate-100">Edit Poli</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
                <!-- Card Header -->
                <div class="p-6 border-b border-slate-100 dark:border-slate-700/50 bg-gradient-to-r from-emerald-600 to-teal-600">
                    <h3 class="text-xl font-bold text-white">Edit Data Poliklinik</h3>
                    <p class="text-emerald-100/80 text-sm mt-1">Perbarui informasi poli "{{ $polyclinic->name }}" termasuk gambar atau ikon-nya.</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('polyclinics.update', $polyclinic->id) }}" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Poli -->
                    <div>
                        <x-input-label for="name" value="Nama Poli" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $polyclinic->name)" required placeholder="Contoh: Poli Umum, Poli Gigi, Poli Anak" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <x-input-label for="description" value="Deskripsi Poli" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Jelaskan layanan yang tersedia di poli ini..." required>{{ old('description', $polyclinic->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Gambar Saat Ini -->
                    @if ($polyclinic->image_path)
                        <div>
                            <x-input-label value="Gambar Saat Ini" />
                            <div class="mt-2 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                                <div class="flex items-start space-x-4">
                                    <div class="w-24 h-24 rounded-xl overflow-hidden border-2 border-emerald-200 dark:border-emerald-800 flex-shrink-0 bg-white dark:bg-slate-800">
                                        <img src="{{ asset($polyclinic->image_path) }}" alt="{{ $polyclinic->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0 pt-1">
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ basename($polyclinic->image_path) }}</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 break-all">Path: {{ $polyclinic->image_path }}</p>
                                        <a href="{{ asset($polyclinic->image_path) }}" target="_blank" class="inline-flex items-center mt-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition">
                                            <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            Buka file di tab baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Upload Gambar Baru -->
                    <div>
                        <x-input-label for="image" value="Unggah Gambar Baru (Opsional)" />
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 mb-2">Biarkan kosong jika tidak ingin mengubah gambar saat ini.</p>
                        <div class="mt-1">
                            <label for="image" class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-emerald-300 dark:border-emerald-700 rounded-xl cursor-pointer bg-emerald-50/50 dark:bg-emerald-950/20 hover:bg-emerald-100/50 dark:hover:bg-emerald-950/40 hover:border-emerald-400 dark:hover:border-emerald-600 transition-all duration-200 group">
                                <div class="flex flex-col items-center justify-center py-4">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="h-5 w-5 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-300 font-medium">Klik untuk mengganti file</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Pilih file pengganti (format bebas)</p>
                                </div>
                                <input id="image" name="image" type="file" class="hidden" />
                            </label>
                            <!-- Preview area -->
                            <div id="image-preview-container" class="hidden mt-3 p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center overflow-hidden flex-shrink-0">
                                        <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p id="image-filename" class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate"></p>
                                        <p id="image-filesize" class="text-xs text-slate-400"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="{{ route('polyclinics.index') }}" class="px-5 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl shadow-md hover:from-emerald-700 hover:to-teal-700 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                            Perbarui Poli
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- File Preview Script -->
    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            const filename = document.getElementById('image-filename');
            const filesize = document.getElementById('image-filesize');

            if (file) {
                previewContainer.classList.remove('hidden');
                filename.textContent = file.name;
                filesize.textContent = (file.size / 1024).toFixed(1) + ' KB';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '';
                    preview.alt = file.name;
                    preview.parentElement.innerHTML = '<svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>';
                }
            } else {
                previewContainer.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
