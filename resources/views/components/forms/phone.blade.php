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
    x-data
    x-on:input="
        let digits = $event.target.value.replace(/\D/g, '');
        if (digits.length > 11) digits = digits.slice(0, 11);
        let formatted = '';
        if (digits.length > 0) formatted = '+' + digits[0];
        if (digits.length > 1) formatted += ' (' + digits.slice(1, 4);
        if (digits.length > 4) formatted += ') ' + digits.slice(4, 7);
        if (digits.length > 7) formatted += '-' + digits.slice(7);
        $event.target.value = formatted;
    "
    {{ $attributes }}
/>
