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
    $hasExisting = !empty($existingImageUrl) || !empty($oldTokenJson);
@endphp

<div>
    @if($label)
        <h3 class="mb-3 text-sm font-semibold text-slate-700 dark:text-neutral-300">{{ $label }}</h3>
    @endif
    <div class="js-image-dropzone"
        data-temp-name="{{ $tempInputName }}"
        data-name="{{ $name }}"
        data-max-size="{{ $maxSize }}"
        data-existing-url="{{ $existingImageUrl }}"
        data-existing-id="{{ $existingImageId }}"
        data-temp='{{ $oldTokenJson }}'>

        <input type="hidden" name="{{ $tempInputName }}" class="temp-input" value="{{ $oldTokenJson }}">
        <input type="file" name="{{ $name }}" accept="{{ $accept }}" class="file-input hidden">

        <div class="dropzone-area text-center cursor-pointer rounded-xl border-2 border-dashed p-8 transition-colors hover:border-slate-400 dark:hover:border-neutral-500 border-slate-300 dark:border-neutral-700 {{ $hasExisting ? 'hidden' : '' }}">
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

        <div class="uploading-area hidden rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm text-slate-600 dark:text-neutral-400">Uploading...</p>
                        <p class="upload-progress text-xs text-slate-500 dark:text-neutral-500">0%</p>
                    </div>
                    <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-neutral-700">
                        <div class="upload-progress-bar h-full rounded-full bg-emerald-500 transition-all duration-200" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="preview-area rounded-xl border bg-slate-50 p-4 transition-colors dark:bg-neutral-900/50 border-slate-200 dark:border-neutral-800 {{ $hasExisting ? '' : 'hidden' }}">
            <div class="flex items-center gap-4">
                <div class="shrink-0">
                    <img class="preview-img h-24 w-24 rounded-lg object-contain ring-1 ring-slate-200 dark:ring-neutral-700"
                        src="{{ $existingImageUrl ?? '' }}">
                </div>
                <div class="min-w-0 flex-1">
                    <p class="preview-name text-sm font-medium text-slate-900 dark:text-white">@if($existingImageUrl)Current image @endif</p>
                    <p class="preview-size text-xs text-slate-400 dark:text-neutral-500"></p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="change-btn inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                        Change
                    </button>
                    <button type="button" class="remove-btn inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900/50 dark:bg-neutral-900 dark:text-red-400 dark:hover:bg-red-900/20">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        Remove
                    </button>
                </div>
            </div>
        </div>

        <p class="error-msg mt-2 text-sm text-red-600 dark:text-red-400 hidden"></p>
    </div>
</div>

@push('scripts')
<script>
(function(){
    var acceptedTypes = {!! Js::from(explode(',', $accept)) !!};

    $('.js-image-dropzone').each(function(){
        var $el = $(this);
        var $tempInput = $el.find('.temp-input');
        var $fileInput = $el.find('.file-input');
        var $dropzone = $el.find('.dropzone-area');
        var $uploading = $el.find('.uploading-area');
        var $preview = $el.find('.preview-area');
        var $errorMsg = $el.find('.error-msg');
        var $progressText = $el.find('.upload-progress');
        var $progressBar = $el.find('.upload-progress-bar');
        var $previewImg = $el.find('.preview-img');
        var $previewName = $el.find('.preview-name');
        var $previewSize = $el.find('.preview-size');
        var tempName = $el.data('temp-name');
        var inputName = $el.data('name');
        var maxSize = $el.data('max-size');
        var existingUrl = $el.data('existing-url') || '';
        var existingId = $el.data('existing-id') || '';
        var tempData = null;
        var uploadUrl = '{{ route('upload-temp') }}';
        var csrfToken = '{{ csrf_token() }}';
        var deleteUrl = '{{ url('/media') }}/';

        if ($tempInput.val()) {
            try {
                tempData = JSON.parse($tempInput.val());
            } catch(e) {}
        }

        function showView(view) {
            $dropzone.addClass('hidden');
            $uploading.addClass('hidden');
            $preview.addClass('hidden');
            if (view === 'dropzone') $dropzone.removeClass('hidden');
            else if (view === 'uploading') $uploading.removeClass('hidden');
            else if (view === 'preview') $preview.removeClass('hidden');
        }

        function setError(msg) {
            if (msg) {
                $errorMsg.text(msg).removeClass('hidden');
            } else {
                $errorMsg.addClass('hidden');
            }
        }

        function updatePreview(data) {
            $previewImg.attr('src', data && data.url ? data.url : existingUrl);
            $previewName.text(data ? data.name : (existingUrl ? 'Current image' : ''));
            $previewSize.text(data && data.size ? formatSize(data.size) : '');
        }

        function formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function resetToDropzone() {
            tempData = null;
            $tempInput.val('');
            showView('dropzone');
            updatePreview(null);
        }

        function handleFile(file) {
            setError('');
            if (!file) return;
            var type = file.type;
            var allowed = acceptedTypes.some(function(t) {
                return type === t.trim() || (t.trim() !== '*' && type.match(t.trim().replace('*', '.*')));
            });
            if (!allowed) {
                setError('Invalid file type. Accepted: {{ $hint }}');
                return;
            }
            if (file.size > maxSize) {
                setError('File size must be less than 2 MB.');
                return;
            }
            showView('uploading');
            $progressText.text('0%');
            $progressBar.css('width', '0%');

            var formData = new FormData();
            formData.append('file', file);
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
                    tempData = data;
                    $tempInput.val(JSON.stringify(data));
                    $previewImg.attr('src', data.url);
                    $previewName.text(data.name);
                    $previewSize.text(formatSize(data.size));
                    showView('preview');
                } else {
                    setError('Upload failed. Please try again.');
                    showView('dropzone');
                }
            };
            xhr.onerror = function() {
                setError('Upload failed. Please try again.');
                showView('dropzone');
            };
            xhr.send(formData);
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
            handleFile(e.originalEvent.dataTransfer.files[0]);
        });

        $preview.on('dragover', function(e){
            e.preventDefault();
            $preview.css('border-color', '#34d399').css('background-color', 'rgb(236 253 245)');
        });
        $preview.on('dragleave', function(e){
            e.preventDefault();
            $preview.css('border-color', '').css('background-color', '');
        });
        $preview.on('drop', function(e){
            e.preventDefault();
            $preview.css('border-color', '').css('background-color', '');
            handleFile(e.originalEvent.dataTransfer.files[0]);
        });

        $fileInput.on('change', function(){
            handleFile(this.files[0]);
            this.value = '';
        });

        $el.find('.change-btn').on('click', function(){ $fileInput.click(); });

        $el.find('.remove-btn').on('click', function(){
            if (existingId) {
                var xhr = new XMLHttpRequest();
                xhr.open('DELETE', deleteUrl + existingId);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.send();
            }
            tempData = null;
            existingUrl = '';
            existingId = '';
            $tempInput.val('');
            resetToDropzone();
        });

        var form = $el.closest('form')[0];
        if (form) {
            $(form).on('submit', function(){
                if (tempData) {
                    $tempInput.val(JSON.stringify(tempData));
                }
            });
        }

        if (tempData) {
            showView('preview');
            updatePreview(tempData);
        } else if (existingUrl) {
            showView('preview');
            $previewImg.attr('src', existingUrl);
            $previewName.text('Current image');
        } else {
            showView('dropzone');
        }
    });
})();
</script>
@endpush
