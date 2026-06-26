@props([
    'name' => 'image',
    'accept' => 'image/jpeg,image/png,image/webp,image/gif',
    'label' => 'Image',
    'hint' => 'PNG, JPG, WebP or GIF (Max. 2MB)',
    'existingImageUrl' => null,
    'existingImageId' => null,
    'maxSize' => 2097152,
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
        previewUrl: null,
        dragging: false,
        error: '',
        uploading: false,
        uploadProgress: 0,
        existingUrl: @js($existingImageUrl),
        existingImageId: @js($existingImageId),
        tempData: @js($oldTokenJson ? json_decode($oldTokenJson, true) : null),
        acceptedTypes: {{ Js::from(explode(',', $accept)) }},
        maxSize: {{ $maxSize }},
        get hasPreview() {
            return !!this.previewUrl || !!this.existingUrl || !!this.tempData;
        },
        handleFile(file) {
            this.error = '';
            if (!file) return;
            var type = file.type;
            var allowed = this.acceptedTypes.some(function(t) {
                return type === t.trim() || type.match(t.trim().replace('*', '.*'));
            });
            if (!allowed) {
                this.error = 'Invalid file type. Accepted: {{ $hint }}';
                return;
            }
            if (file.size > this.maxSize) {
                this.error = 'File size must be less than 2 MB.';
                return;
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
                    this.previewUrl = data.url;
                    this.existingUrl = null;
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
            if (this.existingImageId) {
                var xhr = new XMLHttpRequest();
                xhr.open('DELETE', '{{ url('/media') }}/' + this.existingImageId);
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.send();
            }
            this.file = null;
            this.previewUrl = null;
            this.existingUrl = null;
            this.tempData = null;
            this.$refs.input.value = '';
            this.$refs.tempInput.value = '';
            this.error = '';
        },
        formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        },
    }">
        <input type="hidden" name="{{ $tempInputName }}" x-ref="tempInput" value="{{ $oldTokenJson }}">
        <input type="file" name="{{ $name }}" accept="{{ $accept }}" x-ref="input" @change="handleFile($event.target.files[0])" class="hidden">

        <template x-if="!hasPreview && !uploading">
            <div @click="$refs.input.click()"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="handleFile($event.dataTransfer.files[0]); dragging = false"
                :class="dragging ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-slate-300 dark:border-neutral-700'"
                class="cursor-pointer rounded-xl border-2 border-dashed p-8 text-center transition-colors hover:border-slate-400 dark:hover:border-neutral-500">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/30">
                    <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" /></svg>
                </div>
                <p class="text-sm font-medium text-slate-700 dark:text-neutral-300">
                    <span class="text-emerald-600 dark:text-emerald-400">Click to upload</span>
                    or drag and drop
                </p>
                @if($hint)
                    <p class="mt-1 text-xs text-slate-400 dark:text-neutral-500">{{ $hint }}</p>
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

        <template x-if="hasPreview && !uploading">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                <div class="flex items-center gap-4">
                    <div class="shrink-0">
                        <img :src="previewUrl || existingUrl || (tempData ? tempData.url : null)" class="h-24 w-24 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-neutral-700">
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-900 dark:text-white" x-text="file ? file.name : (tempData ? tempData.name : 'Current image')"></p>
                        <p class="text-xs text-slate-400 dark:text-neutral-500" x-text="file ? formatSize(file.size) : (tempData ? formatSize(tempData.size) : '')"></p>
                    </div>
                    <div class="flex items-center gap-2">
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
