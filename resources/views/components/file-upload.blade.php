@props(['multiple' => false, 'accept' => 'image/*', 'label' => 'Upload files'])

<div class="mt-1" x-data="{ dragging: false, files: [] }">
    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-slate-100 dark:border-slate-600 rounded-xl transition-colors"
        :class="dragging ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : ''"
        @@dragover.prevent="dragging = true"
        @@dragleave.prevent="dragging = false"
        @@drop.prevent="
            dragging = false;
            files = [...files, ...Array.from($event.dataTransfer.files)];
        ">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">
                <span class="cursor-pointer font-medium text-emerald-600 hover:text-emerald-700">Click to upload</span>
                or drag and drop
            </p>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">PNG, JPG, PDF up to 10MB</p>
            <template x-if="files.length > 0">
                <div class="mt-4 text-left">
                    <template x-for="(file, index) in files" :key="index">
                        <div class="mt-1 flex items-center gap-2 rounded-lg bg-white px-3 py-2 dark:bg-slate-800">
                            <svg class="w-4 h-4 shrink-0 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="flex-1 truncate text-sm text-gray-700 dark:text-gray-400" x-text="file.name"></span>
                            <span class="text-xs text-gray-400 dark:text-gray-500" x-text="(file.size / 1024).toFixed(1) + ' KB'"></span>
                            <button @@click="files = files.filter((_, i) => i !== index)" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
    <input type="file" class="hidden" {{ $multiple ? 'multiple' : '' }} accept="{{ $accept }}">
</div>