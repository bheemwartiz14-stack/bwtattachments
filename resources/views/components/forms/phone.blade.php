@props([
    'name' => 'phone',
    'id' => null,
    'label' => 'Phone',
    'value' => '',
    'placeholder' => '+1 (591) 635-5892',
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
