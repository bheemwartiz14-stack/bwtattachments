@props([
    'name' => 'email',
    'id' => null,
    'label' => 'Email',
    'value' => '',
    'placeholder' => 'user@example.com',
    'required' => true,
    'disabled' => false,
    'error' => null,
    'hint' => '',
])

<x-forms.input
    :name="$name"
    :id="$id"
    :label="$label"
    type="email"
    :value="$value"
    :placeholder="$placeholder"
    :required="$required"
    :disabled="$disabled"
    :error="$error"
    :hint="$hint"
    {{ $attributes }}
/>
