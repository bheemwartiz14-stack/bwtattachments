@props([
    'name' => 'product_pdf',
    'accept' => '.pdf,application/pdf',
    'label' => 'Product PDF',
    'hint' => 'PDF only &middot; Max 10 MB',
    'existingFile' => null,
    'existingUrl' => null,
    'existingSize' => null,
    'maxSize' => 10485760,
])

@php
    $tempInputName = $name . '_temp';
    $oldTokenJson = old($tempInputName);
@endphp

<div>
    @if($label)
        <h3 class="mb-3 text-sm font-semibold text-slate-700 dark:text-neutral-300">{{ $label }}</h3>
    @endif
    <div x-data="{
        file: null,
        dragging: false,
        error: '',
        uploading: false,
        uploadProgress: 0,
        existingFile: @js($existingFile),
        existingUrl: @js($existingUrl),
        existingSize: @js($existingSize),
        tempData: @js($oldTokenJson ? json_decode($oldTokenJson, true) : null),
        maxSize: {{ $maxSize }},
        get fileName() {
            if (this.file) return this.file.name;
            if (this.tempData) return this.tempData.name;
            if (this.existingFile) return this.existingFile;
            return '';
        },
        get fileSize() {
            if (this.file) return this.formatSize(this.file.size);
            if (this.tempData) return this.formatSize(this.tempData.size);
            if (this.existingSize) return this.formatSize(this.existingSize);
            return '';
        },
        get hasFile() {
            return !!this.file || !!this.tempData || !!this.existingFile;
        },
        formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        },
        handleFile(file) {
            this.error = '';
            if (!file) return;
            if (file.type !== 'application/pdf') {
                this.error = 'Only PDF files are allowed.';
                return;
            }
            if (file.size > this.maxSize) {
                this.error = 'File size must be less than 10 MB.';
                return;
            }
            if (this.existingUrl) {
                this.$refs.deletedInput.value = '1';
            }
            this.uploading = true;
            this.uploadProgress = 0;
            var formData = new FormData();
            formData.append('file', file);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('upload-temp') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    this.uploadProgress = Math.round((e.loaded / e.total) * 100);
                }
            }.bind(this);
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var data = JSON.parse(xhr.responseText);
                    this.tempData = data;
                    this.file = file;
                    this.existingFile = null;
                    this.existingUrl = null;
                    this.existingSize = null;
                    this.uploading = false;
                    this.$refs.tempInput.value = JSON.stringify(data);
                } else {
                    this.error = 'Upload failed. Please try again.';
                    this.uploading = false;
                }
            }.bind(this);
            xhr.onerror = function() {
                this.error = 'Upload failed. Please try again.';
                this.uploading = false;
            }.bind(this);
            xhr.send(formData);
        },
        removeFile() {
            this.file = null;
            this.tempData = null;
            this.$refs.input.value = '';
            this.$refs.tempInput.value = '';
            this.error = '';
            if (this.existingUrl) {
                this.existingFile = null;
                this.existingUrl = null;
                this.existingSize = null;
                this.$refs.deletedInput.value = '1';
            }
        },
    }">
        <input type="hidden" name="{{ $name }}_deleted" x-ref="deletedInput" value="0">
        <input type="hidden" name="{{ $tempInputName }}" x-ref="tempInput" value="{{ $oldTokenJson }}">
        <input type="file" name="{{ $name }}" accept="{{ $accept }}" x-ref="input" @change="handleFile($event.target.files[0])" class="hidden">

        <template x-if="!hasFile && !uploading">
            <div @click="$refs.input.click()"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="handleFile($event.dataTransfer.files[0]); dragging = false"
                :class="dragging ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-slate-300 dark:border-neutral-700'"
                class="cursor-pointer rounded-xl border-2 border-dashed p-8 text-center transition-colors hover:border-slate-400 dark:hover:border-neutral-500">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                </div>
                <p class="text-sm font-medium text-slate-700 dark:text-neutral-300">
                    <span class="text-emerald-600 dark:text-emerald-400">Click to upload</span>
                    or drag and drop
                </p>
                @if($hint)
                    <p class="mt-1 text-xs text-slate-400 dark:text-neutral-500">{!! $hint !!}</p>
                @endif
            </div>
        </template>

        <template x-if="uploading">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm text-slate-600 dark:text-neutral-400">Uploading...</p>
                            <p class="text-xs text-slate-500 dark:text-neutral-500" x-text="uploadProgress + '%'"></p>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-neutral-700">
                            <div class="h-full rounded-full bg-emerald-500 transition-all duration-200" :style="'width: ' + uploadProgress + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="hasFile && !uploading">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/50">
                        <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-slate-900 dark:text-white" x-text="fileName"></p>
                        <p class="text-xs text-slate-400 dark:text-neutral-500" x-text="fileSize"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <template x-if="existingUrl && !file && !tempData">
                            <a :href="existingUrl" target="_blank" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Download
                            </a>
                        </template>
                        <button type="button" @click="$refs.input.click()" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            Change
                        </button>
                        <button type="button" @click="removeFile()" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900/50 dark:bg-neutral-900 dark:text-red-400 dark:hover:bg-red-900/20">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </template>
        <p x-show="error" x-text="error" class="mt-2 text-sm text-red-600 dark:text-red-400"></p>
    </div>
</div>
