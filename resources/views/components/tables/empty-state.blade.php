@props([
    'title' => 'No data found',
    'description' => 'No records match your criteria. Try adjusting your search or filters.',
    'icon' => true,
    'action' => null,
    'actionLabel' => 'Add New',
    'actionUrl' => null,
    'class' => '',
])

<div class="text-center py-12 {{ $class }}">
    @if($icon)
        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    @endif
    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-neutral-100">{{ $title }}</h3>
    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">{{ $description }}</p>
    @if($action || $actionUrl)
        <div class="mt-6">
            @if($actionUrl)
                <a href="{{ $actionUrl }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-neutral-800 hover:bg-neutral-700 dark:bg-neutral-700 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                    {{ $actionLabel }}
                </a>
            @else
                {{ $action }}
            @endif
        </div>
    @endif
</div>
