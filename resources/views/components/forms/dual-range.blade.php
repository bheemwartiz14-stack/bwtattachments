@props([
    'label' => 'Machine Weight',
    'min' => 0,
    'max' => 10000,
    'minName' => 'min_weight',
    'maxName' => 'max_weight',
    'minValue' => null,
    'maxValue' => null,
    'id' => null,
     'unit' => 'kg',
])

@php
    $componentId = $id ?? 'dual-range-' . \Illuminate\Support\Str::random(6);
    $minVal = $minValue ?? $min;
    $maxVal = $maxValue ?? $max;
@endphp

<div>
    <label class="mb-2 block text-xs font-semibold text-slate-600">
        {{ $label }}
    </label>

    <div class="px-2 pt-2">
        <!-- Slider -->
        <div class="relative h-6">
            <!-- Track -->
            <div class="absolute top-1/2 h-1 w-full -translate-y-1/2 rounded-full bg-slate-200">
            </div>

            <!-- Active range -->
            <div id="{{ $componentId }}-range" class="absolute top-1/2 h-1 -translate-y-1/2 rounded-full bg-blue-600"
                style="left: 0%; right: 0%;"></div>

            <!-- Minimum slider -->
            <input id="{{ $componentId }}-min" type="range" min="{{ $min }}" max="{{ $max }}"
                value="{{ $minVal }}" name="{{ $minName }}" class="weight-slider absolute inset-0 w-full"
                oninput="updateWeightSlider('{{ $componentId }}')" />


            <!-- Maximum slider -->
            <input id="{{ $componentId }}-max" type="range" min="{{ $min }}" max="{{ $max }}"
                value="{{ $maxVal }}" name="{{ $maxName }}" class="weight-slider absolute inset-0 w-full"
                oninput="updateWeightSlider('{{ $componentId }}')" />
        </div>

        <!-- Values -->
        <div class="mt-1 flex justify-between gap-3">
            <div class="flex-1">
                <label class="mb-1 block text-xs text-slate-500">
                    Min {{ $label }}
                </label>
                <div id="{{ $componentId }}-minValue"
                    class="rounded-md bg-white px-3 py-2 text-sm text-slate-600 shadow-sm">
                    {{ number_format($minVal) }} {{ $unit ?? '' }}
                </div>
            </div>
            <div class="flex-1">
                <label class="mb-1 block text-xs text-slate-500">
                    Max {{ $label }}
                </label>

                <div id="{{ $componentId }}-maxValue"
                    class="rounded-md bg-white px-3 py-2 text-sm text-slate-600 shadow-sm">
                    {{ number_format($maxVal) }} {{ $unit ?? '' }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .weight-slider {
        appearance: none;
        pointer-events: none;
        background: transparent;
    }

    .weight-slider::-webkit-slider-thumb {
        appearance: none;
        width: 16px;
        height: 16px;
        border-radius: 9999px;
        background: #2563eb;
        border: 2px solid white;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
        cursor: pointer;
        pointer-events: auto;
    }

    .weight-slider::-moz-range-thumb {
        width: 16px;
        height: 16px;
        border-radius: 9999px;
        background: #2563eb;
        border: 2px solid white;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
        cursor: pointer;
        pointer-events: auto;
    }

    .weight-slider::-webkit-slider-runnable-track {
        background: transparent;
    }

    .weight-slider::-moz-range-track {
        background: transparent;
    }
</style>

@once
    @push('scripts')
        <script>
            window.updateWeightSlider = window.updateWeightSlider || function(componentId) {
                if (!componentId) {
                    // fallback for legacy global ids
                    componentId = '';
                    var minSlider = document.getElementById('minWeightSlider');
                    var maxSlider = document.getElementById('maxWeightSlider');
                    var minEl = document.getElementById('minWeight');
                    var maxEl = document.getElementById('maxWeight');
                    var rangeEl = document.getElementById('weightRange');
                    var maxValAttr = 10000;
                    if (!minSlider || !maxSlider) return;
                    var min = Number(minSlider.value);
                    var max = Number(maxSlider.value);
                    if (min > max) {
                        min = max;
                        minSlider.value = min;
                    }
                    if (max < min) {
                        max = min;
                        maxSlider.value = max;
                    }
                    if (minEl) minEl.textContent = min.toLocaleString() + ' kg';
                    if (maxEl) maxEl.textContent = max.toLocaleString() + ' kg';
                    var pMin = (min / maxValAttr) * 100;
                    var pMax = (max / maxValAttr) * 100;
                    if (rangeEl) {
                        rangeEl.style.left = pMin + '%';
                        rangeEl.style.right = (100 - pMax) + '%';
                    }
                    return;
                }
                var minSlider = document.getElementById(componentId + '-min');
                var maxSlider = document.getElementById(componentId + '-max');
                var minEl = document.getElementById(componentId + '-minValue');
                var maxEl = document.getElementById(componentId + '-maxValue');
                var rangeEl = document.getElementById(componentId + '-range');
                if (!minSlider || !maxSlider) return;
                var minAttr = parseInt(minSlider.getAttribute('min')) || 0;
                var maxAttr = parseInt(minSlider.getAttribute('max')) || 10000;
                var range = maxAttr - minAttr || 1;
                var min = Number(minSlider.value);
                var max = Number(maxSlider.value);
                if (min > max) {
                    min = max;
                    minSlider.value = min;
                }
                if (max < min) {
                    max = min;
                    maxSlider.value = max;
                }
                if (minEl) minEl.textContent = min.toLocaleString() + ' kg';
                if (maxEl) maxEl.textContent = max.toLocaleString() + ' kg';
                var pMin = ((min - minAttr) / range) * 100;
                var pMax = ((max - minAttr) / range) * 100;
                if (rangeEl) {
                    rangeEl.style.left = pMin + '%';
                    rangeEl.style.right = (100 - pMax) + '%';
                }
                // dispatch change for Livewire if needed
                minSlider.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
                maxSlider.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[id$="-min"]').forEach(function(el) {
                    var cid = el.id.replace(/-min$/, '');
                    if (document.getElementById(cid + '-max')) window.updateWeightSlider(cid);
                });
                // legacy
                if (document.getElementById('minWeightSlider')) window.updateWeightSlider();
            });
        </script>
    @endpush
@endonce
