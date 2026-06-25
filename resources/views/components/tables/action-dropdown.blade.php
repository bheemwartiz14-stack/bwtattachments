@props([
    'id' => null,
    'label' => 'Actions',
    'align' => 'right',
    'items' => [],
])

@php
    $dropdownId = $id ?? 'actions-' . uniqid();
    $alignClass = $align === 'right' ? 'right-0 origin-top-right' : 'left-0 origin-top-left';
@endphp

<div class="relative inline-block text-left" data-dropdown-menu-wrapper>
    <button type="button"
        data-dropdown-toggle="{{ $dropdownId }}"
        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-neutral-700 rounded-lg text-sm font-medium text-gray-700 dark:text-neutral-300 bg-white dark:bg-neutral-900 hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-500"
    >
        {{ $label }}
        <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div id="{{ $dropdownId }}"
        data-dropdown-menu
        class="hidden absolute {{ $alignClass }} mt-2 w-48 rounded-lg shadow-lg bg-white dark:bg-neutral-900 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-neutral-800 z-50"
    >
        <div class="py-1">
            @foreach($items as $item)
                @php
                    $url = $item['url'] ?? '#';
                    $method = $item['method'] ?? 'GET';
                    $icon = $item['icon'] ?? null;
                    $color = $item['color'] ?? 'gray';
                    $divider = $item['divider'] ?? false;
                    $confirm = $item['confirm'] ?? null;
                @endphp
                @if($divider)
                    <div class="border-t border-gray-100 dark:border-neutral-800 my-1"></div>
                @endif
                @if(strtolower($method) === 'delete')
                    <form method="POST" action="{{ $url }}" class="inline w-full" @if($confirm) onsubmit="return confirm('{{ $confirm }}')" @endif>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="group flex items-center w-full px-4 py-2 text-sm text-{{ $color }}-700 dark:text-{{ $color }}-300 hover:bg-gray-100 dark:hover:bg-neutral-800">
                            @if($icon)
                                <svg class="mr-3 h-4 w-4 text-{{ $color }}-400 group-hover:text-{{ $color }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                                </svg>
                            @endif
                            {{ $item['label'] ?? 'Action' }}
                        </button>
                    </form>
                @else
                    <a href="{{ $url }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-800">
                        @if($icon)
                            <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                            </svg>
                        @endif
                        {{ $item['label'] ?? 'Action' }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
