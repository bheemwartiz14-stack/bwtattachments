@props([
    'headers' => [],
    'rows' => [],
    'striped' => true,
    'hover' => true,
    'responsive' => true,
    'sortable' => true,
    'sortField' => null,
    'sortDir' => 'asc',
    'bulkActions' => false,
    'class' => '',
])

<div class="overflow-x-auto {{ $responsive ? '-mx-4 sm:mx-0' : '' }}">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200 dark:divide-neutral-800 ' . $class]) }}>
        @if(count($headers) > 0)
            <thead class="bg-gray-50 dark:bg-neutral-900">
                <tr>
                    @if($bulkActions)
                        <th scope="col" class="px-4 py-3 text-left">
                            <input type="checkbox" class="rounded border-gray-300 dark:border-neutral-700 text-neutral-600 focus:ring-neutral-500">
                        </th>
                    @endif
                    @foreach($headers as $header)
                        @php
                            $field = is_array($header) ? ($header['field'] ?? '') : '';
                            $label = is_array($header) ? ($header['label'] ?? $header['field'] ?? '') : $header;
                            $align = is_array($header) ? ($header['align'] ?? 'left') : 'left';
                            $width = is_array($header) ? ($header['width'] ?? '') : '';
                            $currentSort = $sortField === $field;
                            $nextDir = $currentSort && $sortDir === 'asc' ? 'desc' : 'asc';
                        @endphp
                        <th scope="col"
                            class="px-4 py-3 text-{{ $align }} text-xs font-semibold text-gray-500 dark:text-neutral-400 uppercase tracking-wider {{ $sortable && $field ? 'cursor-pointer select-none hover:text-gray-700 dark:hover:text-gray-200' : '' }}"
                            @if($width) style="width: {{ $width }}" @endif
                            @if($sortable && $field) onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => $nextDir]) }}'" @endif
                        >
                            <span class="inline-flex items-center gap-1">
                                {{ $label }}
                                @if($currentSort)
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDir === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        @endif
                                    </svg>
                                @endif
                            </span>
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="bg-white dark:bg-neutral-950 divide-y divide-gray-200 dark:divide-gray-800">
            {{ $slot }}
            @if(count($rows) > 0)
                @foreach($rows as $row)
                    <tr class="{{ $striped ? 'even:bg-gray-50 dark:even:bg-gray-800/50' : '' }} {{ $hover ? 'hover:bg-gray-50 dark:hover:bg-gray-800' : '' }}">
                        @if($bulkActions)
                            <td class="px-4 py-3">
                                <input type="checkbox" class="rounded border-gray-300 dark:border-neutral-700 text-neutral-600 focus:ring-neutral-500">
                            </td>
                        @endif
                        @foreach($headers as $header)
                            @php
                                $field = is_array($header) ? ($header['field'] ?? '') : '';
                                $align = is_array($header) ? ($header['align'] ?? 'left') : 'left';
                                $format = is_array($header) ? ($header['format'] ?? null) : null;
                                $value = $field ? data_get($row, $field, '') : '';
                                if ($format) $value = $format($value, $row);
                            @endphp
                            <td class="px-4 py-3 text-sm text-{{ $align }} text-gray-700 dark:text-neutral-300 whitespace-nowrap">
                                {{ $value }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
