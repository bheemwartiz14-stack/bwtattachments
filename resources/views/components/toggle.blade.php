@props(['checked' => false, 'id' => null])

<label for="{{ $id }}" class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" id="{{ $id }}" class="sr-only peer" {{ $checked ? 'checked' : '' }}>
    <div class="w-9 h-5 bg-slate-300 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
</label>