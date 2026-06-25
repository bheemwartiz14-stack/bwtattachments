@props([
    'name' => '',
    'id' => null,
    'label' => '',
    'accept' => 'image/*',
    'required' => false,
    'error' => null,
    'hint' => '',
    'wrapperClass' => '',
    'maxSizeMB' => 10,
])

@php
    $inputId = $id ?? $name;
    $hasError = $error ? true : ($errors && $errors->has($name));
    $errorMsg = $error ?: ($errors ? $errors->first($name) : '');
    $listId = $inputId . '-list';
@endphp

<div class="{{ $wrapperClass }}" data-dropzone="{{ $inputId }}">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <div data-dropzone-area
        class="flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-xl transition-colors cursor-pointer
               border-gray-300 dark:border-neutral-700
               hover:border-purple-400 dark:hover:border-purple-500
               bg-gray-50 dark:bg-neutral-900/50">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                <span class="font-medium text-purple-600 dark:text-purple-400">Click to upload</span>
                or drag and drop
            </p>
            <p class="mt-1 text-xs text-gray-400">{{ $hint ?: 'PNG, JPG, PDF up to ' . $maxSizeMB . 'MB' }}</p>
        </div>
    </div>

    <input type="file" id="{{ $inputId }}" name="{{ $name }}[]" accept="{{ $accept }}"
        @if($required) required @endif multiple class="hidden">

    <div id="{{ $listId }}" class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4"></div>

    @if($hasError)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errorMsg }}</p>
    @endif
</div>

@push('scripts')
<script>
(function(){
    document.querySelectorAll('[data-dropzone]').forEach(function(el){
        var inputId = el.getAttribute('data-dropzone');
        var input = document.getElementById(inputId);
        var area = el.querySelector('[data-dropzone-area]');
        var list = document.getElementById(inputId + '-list');
        var form = input.closest('form');
        if (!input || !area || !list) return;

        var files = [];

        function render(){
            list.innerHTML = '';
            files.forEach(function(file, index){
                var div = document.createElement('div');
                div.className = 'group relative rounded-lg border border-gray-200 dark:border-neutral-800 overflow-hidden bg-white dark:bg-neutral-900';
                if (file.type && file.type.startsWith('image/')){
                    var img = document.createElement('img');
                    img.className = 'h-28 w-full object-cover';
                    img.src = URL.createObjectURL(file);
                    div.appendChild(img);
                } else {
                    var icon = document.createElement('div');
                    icon.className = 'flex h-28 items-center justify-center text-gray-400';
                    icon.innerHTML = '<svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>';
                    div.appendChild(icon);
                }
                var name = document.createElement('div');
                name.className = 'px-2 py-1 text-xs text-gray-600 dark:text-neutral-400 truncate bg-gray-50 dark:bg-neutral-950';
                name.textContent = file.name;
                div.appendChild(name);

                var overlay = document.createElement('div');
                overlay.className = 'absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity';
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'rounded-full bg-red-500 p-2 text-white hover:bg-red-600';
                btn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                btn.addEventListener('click', function(e){
                    e.stopPropagation();
                    files.splice(index, 1);
                    render();
                });
                overlay.appendChild(btn);
                div.appendChild(overlay);
                list.appendChild(div);
            });
        }

        function syncInput(){
            var dt = new DataTransfer();
            files.forEach(function(f){ dt.items.add(f); });
            input.files = dt.files;
        }

        area.addEventListener('click', function(){ input.click(); });

        input.addEventListener('change', function(){
            Array.from(this.files).forEach(function(f){
                files.push(f);
            });
            render();
            this.value = '';
        });

        area.addEventListener('dragover', function(e){
            e.preventDefault();
            area.classList.add('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
        });
        area.addEventListener('dragleave', function(){
            area.classList.remove('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
        });
        area.addEventListener('drop', function(e){
            e.preventDefault();
            area.classList.remove('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
            Array.from(e.dataTransfer.files).forEach(function(f){
                files.push(f);
            });
            render();
        });

        if (form) {
            form.addEventListener('submit', function(){
                syncInput();
            });
        }
    });
})();
</script>
@endpush
