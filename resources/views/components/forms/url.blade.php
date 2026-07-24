@props([
    'name' => 'url',
    'id' => null,
    'label' => 'URL',
    'value' => '',
    'placeholder' => 'www.example.com',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => '',
])

<x-forms.input
    :name="$name"
    :id="$id"
    :label="$label"
    type="url"
    :value="$value"
    :placeholder="$placeholder"
    :required="$required"
    :disabled="$disabled"
    :error="$error"
    :hint="$hint"
    x-data
    x-init="$nextTick(() => {
        $el.addEventListener('blur', function() {
            let val = this.value.trim();
            if (val && !/^https?:\/\//i.test(val)) {
                this.value = 'https://' + val;
            }
        });
    })"
    {{ $attributes }}
/>
