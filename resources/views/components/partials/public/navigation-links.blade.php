@php
    use App\Models\Category;

    $cats = \App\Models\Category::whereIn('name', ['Excavator Attachments', 'Wheel Loader Attachments', 'Wear Parts', 'Spare Parts'])->get()->keyBy('name');

    $linkClass = isset($mobile)
        ? 'block rounded-lg px-3 py-2.5 text-sm font-medium text-white hover:bg-white/10 transition-colors'
        : 'hover:text-blue-200 transition-colors';
@endphp

@foreach ($cats as $category)
        <a href="{{ route('public.categories.show', $category) }}" wire:navigate class="{{ $linkClass }}">
            {{ $category->name }}
        </a>
@endforeach

<a href="{{ route('public.reseller-program.index') }}" wire:navigate class="{{ $linkClass }}">
    Reseller Program
</a>

<a href="{{ route('public.contact.index') }}" wire:navigate class="{{ $linkClass }}">
    Contact
</a>
