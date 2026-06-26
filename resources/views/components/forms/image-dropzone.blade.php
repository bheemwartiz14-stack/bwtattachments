@props([
    'name' => 'image',
    'accept' => 'image/jpeg,image/png,image/webp,image/gif',
    'label' => 'Image',
    'hint' => 'PNG, JPG, WebP or GIF (Max. 2MB)',
    'existingImageUrl' => null,
    'maxSize' => 2097152,
    'name' => 'Image',
])

<div>
    @if($label)
        <h3 class="mb-3 text-sm font-semibold text-slate-700 dark:text-neutral-300">{{ $label }}</h3>
    @endif
    <div x-data="{
        file: null,
        previewUrl: null,
        dragging: false,
        error: '',
        existingUrl: @js($existingImageUrl),
        acceptedTypes: {{ Js::from(explode(',', $accept)) }},
        maxSize: {{ $maxSize }},
        get hasPreview() {
            return !!this.previewUrl || !!this.existingUrl;
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
            this.file = file;
            var reader = new FileReader();
            reader.onload = function(e) {
                this.previewUrl = e.target.result;
                this.existingUrl = null;
            }.bind(this);
            reader.readAsDataURL(file);
        },
        removeFile() {
            this.file = null;
            this.previewUrl = null;
            this.$refs.input.value = '';
            this.error = '';
        },
    }">
        <input type="file" name="{{ $name }}" accept="{{ $accept }}" x-ref="input" @change="handleFile($event.target.files[0])" class="hidden">
        <template x-if="!hasPreview">
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
        <template x-if="hasPreview">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                <div class="flex items-center gap-4">
                    <div class="shrink-0">
                        <img :src="previewUrl || existingUrl" class="h-24 w-24 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-neutral-700">
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-900 dark:text-white" x-text="file ? file.name : 'Current image'"></p>
                        <p class="text-xs text-slate-400 dark:text-neutral-500" x-text="file ? formatSize(file.size) : ''"></p>
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
