@if ($errors->any())
    <div class="mx-8 mt-6 rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
        <div class="flex items-start gap-3">

            <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">

                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>

            <div>
                <p class="text-sm font-semibold text-red-800 dark:text-red-300">
                    Please fix {{ $errors->count() > 1
                        ? 'these ' . $errors->count() . ' errors'
                        : 'this error'
                    }}:
                </p>

                <ul class="mt-1.5 list-disc space-y-1 text-sm text-red-700 dark:text-red-400 [&_li]:ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
@endif