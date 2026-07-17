@props([
    'product',
    'isFavorited' => false,
    'variant' => 'inline',
])

@php
    $favId = $product instanceof \App\Models\Product ? $product->id : $product;
    $classes = match ($variant) {
        'absolute' => 'absolute bottom-2 right-2 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-white/80 shadow-sm backdrop-blur-sm transition-all hover:scale-110',
        default => 'inline-flex items-center justify-center rounded-lg p-2 text-red-400 transition-colors hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-400',
    };
    $iconSize = $variant === 'absolute' ? 'h-6 w-6' : 'h-4 w-4';
@endphp

<button type="button"
    data-favorite="{{ $favId }}"
    data-favorited="{{ $isFavorited ? 'true' : 'false' }}"
    onclick="toggleFavorite(this)"
    class="{{ $classes }}"
    title="{{ $isFavorited ? 'Remove from favorites' : 'Add to favorites' }}">
    @svg('heroicon-o-heart', $iconSize . ' text-red-500' . ($isFavorited ? ' hidden' : ''))
    @svg('heroicon-s-heart', $iconSize . ' text-red-500' . ($isFavorited ? '' : ' hidden'))
</button>

<script>
        if (!window.toggleFavorite) {
            window.toggleFavorite = function(btn) {
            var $btn = $(btn);
            var productId = $btn.data('favorite');
            var url = '/favorites/toggle/' + productId;
            var csrf = $('meta[name="csrf-token"]').attr('content');

            $.post(url, { _token: csrf }, function (res) {
                $btn.data('favorited', res.favorited);
                $btn.attr('title', res.favorited ? 'Remove from favorites' : 'Add to favorites');
                $btn.find('svg').toggleClass('hidden');
                if (window.showToast) {
                    window.showToast(res.message, res.favorited ? 'success' : 'info');
                }
            }).fail(function () {
                if (window.showToast) {
                    window.showToast('Failed to update favorite', 'error');
                }
            });
        };
        }
    </script>
