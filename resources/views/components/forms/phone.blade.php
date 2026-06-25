@props([
    'name' => 'phone',
    'id' => null,
    'label' => 'Phone',
    'value' => '',
    'placeholder' => '+1 (555) 000-0000',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => '',
])

<x-forms.input
    :name="$name"
    :id="$id"
    :label="$label"
    type="tel"
    :value="$value"
    :placeholder="$placeholder"
    :required="$required"
    :disabled="$disabled"
    :error="$error"
    :hint="$hint"
    {{ $attributes }}
/>
