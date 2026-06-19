@props(['min' => 0, 'max' => 100, 'step' => 1, 'value' => null, 'label' => '', 'unit' => ''])

<div x-data="{
    min: {{ $min }},
    max: {{ $max }},
    val: {{ $value ?? $min }},
    updateVal(event) {
        this.val = event.target.value;
    }
}" class="space-y-2">
    <div class="flex items-center justify-between">
        <label class="mb-0 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ $label }}</label>
        <span class="text-sm font-medium text-black dark:text-gray-100" x-text="val + ' {{ $unit }}'"></span>
    </div>
    <input type="range" :min="min" :max="max" step="{{ $step }}"
        x-model="val"
        @@input="updateVal"
        class="h-2 w-full appearance-none cursor-pointer rounded-full bg-slate-100 accent-emerald-600 dark:bg-slate-700">
</div>
