@props([
    'name' => '',
    'id' => null,
    'label' => '',
    'options' => [],
    'value' => '',
    'selected' => '',
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'error' => null,
    'hint' => '',
    'wrapperClass' => '',
    'select2' => false,
])

@php
    $inputId = $id ?? $name;
    $hasError = $error ? true : $errors && $errors->has($name);
    $errorMsg = $error ?: ($errors ? $errors->first($name) : '');
@endphp

<div class="{{ $wrapperClass }}">

    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
            {{ $label }}

            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select name="{{ $name }}{{ $multiple ? '[]' : '' }}" id="{{ $inputId }}"
        @if ($multiple) multiple @endif @if ($required) required @endif
        @if ($disabled) disabled @endif
        {{ $attributes->merge([
            'class' =>
                ($select2 ? 'select2 ' : '') .
                'block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none ' .
                ($hasError ? 'has-error' : ''),
        ]) }}>

        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $key => $option)
            @php
                if (is_array($option)) {
                    $optionValue = $option['id'] ?? ($option['iso_code'] ?? $key);
                    $optionName = $option['country'] ?? $optionValue;
                    $isoId = $option['iso_code'];
                } else {
                    $optionValue = $key;
                    $optionName = $option;
                    $isoId = $key;
                }
                $isSelected = $multiple ? in_array($optionValue, (array) $selected) : $selected == $optionName;
            @endphp

            <option value="{{ $optionValue }}" data-iso-id="{{ $isoId }}" data-name="{{ $optionName }}"
                @selected($isSelected)>
                {{ $optionName }}
            </option>
        @endforeach
    </select>
    @if ($hint && !$hasError)
        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">
            {{ $hint }}
        </p>
    @endif

    @if ($hasError)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
            {{ $errorMsg }}
        </p>
    @endif

</div>


@if ($select2)
    @once
        @push('scripts')
            <script>
                $(document).ready(function() {
                    $('.select2').each(function() {
                        const $select = $(this);
                        if (!$select.hasClass('select2-hidden-accessible')) {
                            $select.select2({
                                width: '100%',
                                placeholder: $select.find('option[value=""]').text() ||
                                    'Select an option',
                                allowClear: true,
                            });

                        }
                    });
                });
            </script>
        @endpush

    @endonce
@endif
