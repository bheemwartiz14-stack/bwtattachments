@props(['items' => []])

<nav class="flex mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500">
        <li>
            <a href="{{ $home ?? '/' }}" class="transition-colors hover:text-gray-700 dark:hover:text-slate-300">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </a>
        </li>
        @foreach($items as $item)
            <li class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="transition-colors hover:text-gray-700 dark:hover:text-slate-300">{{ $item['label'] }}</a>
                @else
                    <span class="font-medium text-black dark:text-gray-400">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
