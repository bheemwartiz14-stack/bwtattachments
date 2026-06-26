@props([
    'name' => 'gallery',
    'accept' => 'image/*',
    'label' => 'Gallery Images',
    'hint' => 'PNG, JPG, WebP or GIF',
    'existingImages' => [],
    'maxSize' => 2097152,
])

@php
    $existing = [];
    foreach ($existingImages as $img) {
        $existing[] = [
            'type' => 'existing',
            'id' => $img['id'] ?? '',
            'url' => $img['url'] ?? '',
            'name' => $img['name'] ?? 'image',
        ];
    }
    $tempInputName = $name . '_temp';
    $oldGalleryTemp = old($tempInputName);
@endphp

<div>
    @if($label)
        <h3 class="mb-3 text-sm font-semibold text-slate-700 dark:text-neutral-300">{{ $label }}</h3>
    @endif
    <div x-data="{
        items: {{ json_encode($existing) }},
        deletedIds: [],
        dragging: false,
        error: '',
        uploading: false,
        maxSize: {{ $maxSize }},
        acceptedTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
        init() {
            var form = this.$el.closest('form');
            if (form) {
                form.addEventListener('submit', this.syncInput.bind(this));
            }
            var oldVal = this.$refs.tempInput.value;
            if (oldVal) {
                try {
                    var parsed = JSON.parse(oldVal);
                    if (Array.isArray(parsed)) {
                        parsed.forEach(function(t) {
                            this.items.push({
                                type: 'temp',
                                tempToken: t.token,
                                url: t.url,
                                name: t.name,
                                size: t.size,
                            });
                        }.bind(this));
                    }
                } catch(e) {}
            }
        },
        uploadFile(file, callback) {
            var formData = new FormData();
            formData.append('file', file);
            this.uploading = true;
            fetch('{{ route('admin.upload-temp') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                this.uploading = false;
                callback(data);
            }.bind(this))
            .catch(function() {
                this.uploading = false;
                this.error = 'Upload failed. Please try again.';
            }.bind(this));
        },
        addFiles(newFiles) {
            this.error = '';
            Array.from(newFiles).forEach(function(f) {
                var type = f.type;
                var allowed = this.acceptedTypes.some(function(t) { return type === t; });
                if (!allowed) {
                    this.error = 'Only image files are allowed (PNG, JPG, WebP, GIF).';
                    return;
                }
                if (f.size > this.maxSize) {
                    this.error = 'Each image must be less than 2 MB.';
                    return;
                }
                this.uploadFile(f, function(data) {
                    this.items.push({
                        type: 'temp',
                        tempToken: data.token,
                        url: data.url,
                        name: data.name,
                        size: data.size,
                    });
                }.bind(this));
            }.bind(this));
        },
        replaceItem(index) {
            var input = document.createElement('input');
            input.type = 'file';
            input.accept = '{{ $accept }}';
            input.onchange = function(e) {
                var f = e.target.files[0];
                if (!f) return;
                var type = f.type;
                var allowed = this.acceptedTypes.some(function(t) { return type === t; });
                if (!allowed) {
                    this.error = 'Only image files are allowed (PNG, JPG, WebP, GIF).';
                    return;
                }
                if (f.size > this.maxSize) {
                    this.error = 'Each image must be less than 2 MB.';
                    return;
                }
                var item = this.items[index];
                if (item.type === 'existing') {
                    this.deletedIds.push(item.id);
                }
                this.uploadFile(f, function(data) {
                    this.items[index] = {
                        type: 'temp',
                        tempToken: data.token,
                        url: data.url,
                        name: data.name,
                        size: data.size,
                    };
                    this.error = '';
                }.bind(this));
            }.bind(this);
            input.click();
        },
        removeItem(index) {
            var item = this.items[index];
            if (item.type === 'existing') {
                this.deletedIds.push(item.id);
            }
            this.items.splice(index, 1);
        },
        formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        },
        syncInput() {
            var tempItems = [];
            this.items.forEach(function(item) {
                if (item.type === 'temp' && item.tempToken) {
                    tempItems.push({
                        token: item.tempToken,
                        url: item.url,
                        name: item.name,
                        size: item.size,
                    });
                }
            });
            this.$refs.tempInput.value = JSON.stringify(tempItems);
            this.$refs.deletedInput.value = JSON.stringify(this.deletedIds);
        },
    }">
        <input type="hidden" name="deleted_gallery" x-ref="deletedInput">
        <input type="hidden" name="{{ $tempInputName }}" x-ref="tempInput" value="{{ $oldGalleryTemp }}">
        <input type="file" accept="{{ $accept }}" x-ref="input" @change="addFiles($event.target.files); $refs.input.value = ''" multiple class="hidden">

        <div @click="uploading ? null : $refs.input.click()"
            @dragover.prevent="uploading ? null : (dragging = true)"
            @dragleave.prevent="dragging = false"
            @drop.prevent="uploading ? null : (addFiles($event.dataTransfer.files); dragging = false)"
            :class="dragging ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-slate-300 dark:border-neutral-700'"
            class="cursor-pointer rounded-xl border-2 border-dashed p-6 text-center transition-colors hover:border-slate-400 dark:hover:border-neutral-500">
            <template x-if="!uploading">
                <div>
                    <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/30">
                        <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
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
                <div class="flex items-center justify-center gap-2">
                    <div class="h-5 w-5 animate-spin rounded-full border-2 border-emerald-500 border-t-transparent"></div>
                    <p class="text-sm text-slate-500 dark:text-neutral-400">Uploading...</p>
                </div>
            </template>
        </div>

        <template x-if="items.length">
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                <template x-for="(item, i) in items" :key="i">
                    <div class="group relative overflow-hidden rounded-lg border border-slate-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                        <img :src="item.url" class="h-32 w-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                            <p class="truncate text-xs text-white" x-text="item.name"></p>
                        </div>
                        <div class="absolute right-1 top-1 flex gap-1">
                            <button type="button" @click="replaceItem(i)" class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-500 text-white hover:bg-blue-600 shadow-sm" title="Replace">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            </button>
                            <button type="button" @click="removeItem(i)" class="flex h-7 w-7 items-center justify-center rounded-full bg-red-500 text-white hover:bg-red-600 shadow-sm" title="Remove">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </template>
        <p x-show="error" x-text="error" class="mt-2 text-sm text-red-600 dark:text-red-400"></p>
    </div>
</div>
