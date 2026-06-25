<button
    type="submit"
    {{ $attributes->merge(['class' => 'btn-primary flex items-center gap-2']) }}
>
    <span>{{ $text }}</span>
</button>