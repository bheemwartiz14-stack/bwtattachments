<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-neutral-950 via-neutral-900 to-neutral-950 p-4 sm:p-6 md:p-8 shadow-2xl">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-40"></div>
    <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            @if($icon ?? false)
                <div class="flex h-10 w-10 sm:h-12 sm:w-12 md:h-14 md:w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-sm">
                   @svg($icon, 'h-5 w-5 sm:h-6 sm:w-6 md:h-7 md:w-7 text-white')
                </div>
            @endif
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold tracking-tight text-white">{{ $title }}</h1>
                @if($subtitle ?? false)
                    <p class="mt-1.5 text-xs sm:text-sm text-neutral-400">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @if($actions ?? false)
            <div class="self-end sm:self-auto shrink-0">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
