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
    $parsedOld = $oldGalleryTemp ? json_decode($oldGalleryTemp, true) : [];
@endphp

<div>
    @if($label)
        <h3 class="mb-3 text-sm font-semibold text-slate-700 dark:text-neutral-300">{{ $label }}</h3>
    @endif
    <div class="js-gallery-dropzone"
        data-temp-name="{{ $tempInputName }}"
        data-max-size="{{ $maxSize }}"
        data-existing='{{ json_encode($existing) }}'
        data-old-temp='{{ $oldGalleryTemp ?? '' }}'>

        <input type="hidden" name="{{ $tempInputName }}" class="temp-input" value="{{ $oldGalleryTemp }}">
        <input type="file" accept="{{ $accept }}" class="file-input hidden" multiple>

        <div class="dropzone-area cursor-pointer rounded-xl border-2 border-dashed p-6 text-center transition-colors hover:border-slate-400 dark:hover:border-neutral-500 border-slate-300 dark:border-neutral-700">
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
        </div>

        <div class="uploading-area hidden mt-2">
            <div class="space-y-2">
                <div class="flex items-center justify-center gap-2">
                    <div class="h-5 w-5 animate-spin rounded-full border-2 border-emerald-500 border-t-transparent"></div>
                    <p class="text-sm text-slate-500 dark:text-neutral-400">Uploading...</p>
                    <p class="upload-progress text-xs text-slate-500 dark:text-neutral-500">0%</p>
                </div>
                <div class="h-1.5 w-full max-w-xs mx-auto rounded-full bg-slate-200 dark:bg-neutral-700">
                    <div class="upload-progress-bar h-full rounded-full bg-emerald-500 transition-all duration-200" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <div class="items-grid mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 {{ empty($existing) && empty($parsedOld) ? 'hidden' : '' }}"></div>

        <p class="error-msg mt-2 text-sm text-red-600 dark:text-red-400 hidden"></p>
    </div>
</div>

@push('scripts')
<script>
(function(){
    var acceptedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    $('.js-gallery-dropzone').each(function(){
        var $el = $(this);
        var $tempInput = $el.find('.temp-input');
        var $fileInput = $el.find('.file-input');
        var $dropzone = $el.find('.dropzone-area');
        var $uploading = $el.find('.uploading-area');
        var $grid = $el.find('.items-grid');
        var $errorMsg = $el.find('.error-msg');
        var $progressText = $el.find('.upload-progress');
        var $progressBar = $el.find('.upload-progress-bar');

        var maxSize = $el.data('max-size');
        var uploadUrl = '{{ route('upload-temp') }}';
        var csrfToken = '{{ csrf_token() }}';
        var deleteUrl = '{{ url('/media') }}/';

        var items = [];

        function initItems() {
            var existing = $el.data('existing') || [];
            existing.forEach(function(img) {
                items.push({
                    type: 'existing',
                    id: img.id,
                    url: img.url,
                    name: img.name
                });
            });
            var oldTemp = $el.data('old-temp');
            if (oldTemp) {
                try {
                    var parsed = JSON.parse(oldTemp);
                    if (Array.isArray(parsed)) {
                        parsed.forEach(function(t) {
                            items.push({
                                type: 'temp',
                                tempToken: t.token,
                                url: t.url,
                                name: t.name,
                                size: t.size
                            });
                        });
                    }
                } catch(e) {}
            }
        }

        function setError(msg) {
            if (msg) {
                $errorMsg.text(msg).removeClass('hidden');
            } else {
                $errorMsg.addClass('hidden');
            }
        }

        function formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function render() {
            $grid.empty();
            if (items.length === 0) {
                $grid.addClass('hidden');
                return;
            }
            $grid.removeClass('hidden');
            items.forEach(function(item, i){
                var $div = $('<div>').addClass('group relative overflow-hidden rounded-lg border border-slate-200 dark:border-neutral-800 bg-white dark:bg-neutral-900');
                var $img = $('<img>').addClass('h-32 w-full object-cover').attr('src', item.url);
                $div.append($img);

                var $caption = $('<div>').addClass('absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-2');
                var $name = $('<p>').addClass('truncate text-xs text-white').text(item.name);
                $caption.append($name);
                $div.append($caption);

                var $actions = $('<div>').addClass('absolute right-1 top-1 flex gap-1');
                var $replaceBtn = $('<button>').addClass('replace-btn flex h-7 w-7 items-center justify-center rounded-full bg-blue-500 text-white hover:bg-blue-600 shadow-sm').attr('title', 'Replace').attr('data-index', i);
                $replaceBtn.html('<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>');
                $actions.append($replaceBtn);

                var $removeBtn = $('<button>').addClass('remove-btn flex h-7 w-7 items-center justify-center rounded-full bg-red-500 text-white hover:bg-red-600 shadow-sm').attr('title', 'Remove').attr('data-index', i);
                $removeBtn.html('<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>');
                $actions.append($removeBtn);
                $div.append($actions);

                $grid.append($div);
            });
        }

        function addFiles(newFiles) {
            setError('');
            var files = Array.from(newFiles);
            if (files.length === 0) return;

            var uploadingCount = files.length;
            $uploading.removeClass('hidden');
            $progressText.text('0%');
            $progressBar.css('width', '0%');
            var completed = 0;

            function checkDone() {
                if (completed >= uploadingCount) {
                    $uploading.addClass('hidden');
                }
            }

            files.forEach(function(f){
                var type = f.type;
                var allowed = acceptedTypes.some(function(t) { return type === t; });
                if (!allowed) {
                    setError('Only image files are allowed (PNG, JPG, WebP, GIF).');
                    uploadingCount--;
                    checkDone();
                    return;
                }
                if (f.size > maxSize) {
                    setError('Each image must be less than 2 MB.');
                    uploadingCount--;
                    checkDone();
                    return;
                }

                var formData = new FormData();
                formData.append('file', f);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', uploadUrl);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.upload.onprogress = function(e) {
                    if (e.lengthComputable) {
                        var pct = Math.round((e.loaded / e.total) * 100);
                        $progressText.text(pct + '%');
                        $progressBar.css('width', pct + '%');
                    }
                };
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        var data = JSON.parse(xhr.responseText);
                        items.push({
                            type: 'temp',
                            tempToken: data.token,
                            url: data.url,
                            name: data.name,
                            size: data.size
                        });
                        render();
                    } else {
                        setError('Upload failed. Please try again.');
                    }
                    completed++;
                    checkDone();
                };
                xhr.onerror = function() {
                    setError('Upload failed. Please try again.');
                    completed++;
                    checkDone();
                };
                xhr.send(formData);
            });
        }

        function replaceItem(index) {
            var input = document.createElement('input');
            input.type = 'file';
            input.accept = '{{ $accept }}';
            input.onchange = function(e) {
                var f = e.target.files[0];
                if (!f) return;
                var type = f.type;
                var allowed = acceptedTypes.some(function(t) { return type === t; });
                if (!allowed) {
                    setError('Only image files are allowed (PNG, JPG, WebP, GIF).');
                    return;
                }
                if (f.size > maxSize) {
                    setError('Each image must be less than 2 MB.');
                    return;
                }
                var item = items[index];
                if (item.type === 'existing' && item.id) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('DELETE', deleteUrl + item.id);
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                    xhr.send();
                }

                var uploadingCount = 1;
                $uploading.removeClass('hidden');
                $progressText.text('0%');
                $progressBar.css('width', '0%');

                var formData = new FormData();
                formData.append('file', f);
                var xhr2 = new XMLHttpRequest();
                xhr2.open('POST', uploadUrl);
                xhr2.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr2.upload.onprogress = function(ev) {
                    if (ev.lengthComputable) {
                        var pct = Math.round((ev.loaded / ev.total) * 100);
                        $progressText.text(pct + '%');
                        $progressBar.css('width', pct + '%');
                    }
                };
                xhr2.onload = function() {
                    if (xhr2.status >= 200 && xhr2.status < 300) {
                        var data = JSON.parse(xhr2.responseText);
                        items[index] = {
                            type: 'temp',
                            tempToken: data.token,
                            url: data.url,
                            name: data.name,
                            size: data.size
                        };
                        setError('');
                        render();
                    } else {
                        setError('Upload failed. Please try again.');
                    }
                    $uploading.addClass('hidden');
                };
                xhr2.onerror = function() {
                    setError('Upload failed. Please try again.');
                    $uploading.addClass('hidden');
                };
                xhr2.send(formData);
            };
            input.click();
        }

        $dropzone.on('click', function(){ $fileInput.click(); });
        $dropzone.on('dragover', function(e){
            e.preventDefault();
            $dropzone.css('border-color', '#34d399').css('background-color', 'rgb(236 253 245)');
        });
        $dropzone.on('dragleave', function(e){
            e.preventDefault();
            $dropzone.css('border-color', '').css('background-color', '');
        });
        $dropzone.on('drop', function(e){
            e.preventDefault();
            $dropzone.css('border-color', '').css('background-color', '');
            addFiles(e.originalEvent.dataTransfer.files);
        });

        $fileInput.on('change', function(){
            addFiles(this.files);
            this.value = '';
        });

        $grid.on('click', '.replace-btn', function(){
            replaceItem(parseInt($(this).data('index')));
        });

        $grid.on('click', '.remove-btn', function(){
            var i = parseInt($(this).data('index'));
            var item = items[i];
            if (item.type === 'existing' && item.id) {
                var xhr = new XMLHttpRequest();
                xhr.open('DELETE', deleteUrl + item.id);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.send();
            }
            items.splice(i, 1);
            render();
        });

        var form = $el.closest('form')[0];
        if (form) {
            $(form).on('submit', function(){
                var tempItems = [];
                items.forEach(function(item){
                    if (item.type === 'temp' && item.tempToken) {
                        tempItems.push({
                            token: item.tempToken,
                            url: item.url,
                            name: item.name,
                            size: item.size
                        });
                    }
                });
                $tempInput.val(JSON.stringify(tempItems));
            });
        }

        initItems();
        render();
    });
})();
</script>
@endpush
