@props(['headers' => [], 'rows' => [], 'sortable' => true])

<div class="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
    @if(isset($header))
        <div class="border-b border-slate-100 px-6 py-4 dark:border-slate-700">
            {{ $header }}
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-white dark:border-slate-700 dark:bg-slate-800/50">
                    @foreach($headers as $header)
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 {{ $sortable ? 'cursor-pointer hover:text-gray-700 dark:hover:text-slate-300' : '' }}">
                            <div class="flex items-center gap-1">
                                {{ $header }}
                                @if($sortable)
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    @if(isset($footer))
        <div class="border-t border-slate-100 bg-white px-6 py-3 dark:border-slate-700 dark:bg-slate-800/50">
            {{ $footer }}
        </div>
    @endif
</div>