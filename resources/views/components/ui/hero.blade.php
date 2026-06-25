<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-neutral-950 via-neutral-900 to-neutral-950 p-8 shadow-2xl">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-40"></div>
    <div class="relative flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @if($icon ?? false)
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-sm">
                    {{ $icon }}
                </div>
            @endif
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white">{{ $title }}</h1>
                @if($subtitle ?? false)
                    <p class="mt-1.5 text-sm text-neutral-400">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @if($actions ?? false)
            <div class="shrink-0">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
