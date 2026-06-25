@props([
    'name' => '',
    'id' => null,
    'label' => '',
    'value' => '',
    'placeholder' => '0.00',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'hint' => '',
    'wrapperClass' => '',
    'symbol' => config('app.currency_symbol'),
])

<x-forms.input
    :name="$name"
    :id="$id"
    :label="$label"
    type="number"
    step="0.01"
    min="0"
    :value="$value"
    :placeholder="$placeholder"
    :required="$required"
    :disabled="$disabled"
    :readonly="$readonly"
    :error="$error"
    :hint="$hint"
    :wrapperClass="$wrapperClass"
    :prepend="$symbol"
    {{ $attributes }}
/>
