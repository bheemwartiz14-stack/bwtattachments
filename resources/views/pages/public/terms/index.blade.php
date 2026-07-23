<x-layouts.app>
    <x-slot:title>Terms & Conditions - {{ $siteTitle }}</x-slot:title>

    <div class="space-y-6">
        <x-ui.hero title="Terms & Conditions" />

        @if($terms->isEmpty())
            <div class="rounded-2xl border border-slate-200 bg-white px-8 py-12 text-center shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">No Terms Available</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">No terms and conditions documents have been published yet.</p>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($terms as $term)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30">
                                <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ $term->title }}</h3>
                                @if($term->description)
                                    <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">{{ $term->description }}</p>
                                @endif
                                <div class="mt-3 flex items-center gap-4 text-xs text-slate-400 dark:text-neutral-500">
                                    <span>Uploaded {{ $term->created_at->format('d M Y') }}</span>
                                    @if($term->createdBy)
                                        <span>by {{ $term->createdBy->name }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($term->getFirstMedia('file'))
                                <a href="{{ $term->getFirstMedia('file')->getUrl() }}" target="_blank"
                                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    View
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
