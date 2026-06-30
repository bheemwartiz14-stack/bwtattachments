<x-layouts.app>
    <x-slot:title>Create Quotation - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Client Portal', 'url' => route('client.dashboard')], ['label' => 'My Quotations', 'url' => route('client.quotations.index')], ['label' => 'Create Quotation']]" />

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 dark:border-red-900/50 dark:bg-red-900/30">
            <ul class="list-disc pl-5 text-sm text-red-800 dark:text-red-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-5xl mx-auto space-y-6" id="quotation-app">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Create Quotation</h1>
                <p class="text-sm text-gray-700 mt-1">Select products and set your margin to build a quotation</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-neutral-100 mb-4">Search Products</h2>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" id="search-query" placeholder="Search by product code or description..." class="block w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                    </div>
                    <div id="search-results" class="mt-4 hidden divide-y divide-slate-100 dark:divide-neutral-800 max-h-80 overflow-y-auto rounded-xl border border-slate-100 dark:border-neutral-800"></div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-neutral-800 flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-black dark:text-neutral-100">Selected Items</h2>
                        <span id="item-count" class="text-xs font-medium text-gray-400 dark:text-neutral-500">0 item(s)</span>
                    </div>
                    <div id="selected-items" class="divide-y divide-slate-100 dark:divide-neutral-800">
                        <div class="px-6 py-12 text-center empty-state">
                            <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-neutral-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                            <p class="mt-3 text-sm text-gray-400 dark:text-neutral-500">No items selected</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Search and add products above</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <form method="POST" action="{{ route('client.quotations.store') }}" class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950 sticky top-20">
                    @csrf
                    <input type="hidden" name="items" id="items-input" value="[]">
                    <input type="hidden" name="margin_percentage" id="margin-input" value="10">

                    <h2 class="text-base font-semibold text-slate-950 dark:text-neutral-100 mb-4">Summary</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Margin %</label>
                            <div class="flex items-center gap-3">
                                <input type="range" id="margin-slider" min="0" max="50" value="10" class="w-full h-2 bg-slate-100 dark:bg-neutral-900 rounded-full appearance-none cursor-pointer accent-emerald-600">
                                <div class="flex items-center gap-1">
                                    <input type="number" id="margin-value" min="0" max="100" value="10" class="block w-16 rounded-lg border border-slate-200 bg-white px-2 py-1 text-sm text-black text-center placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                                    <span class="text-sm text-gray-400 dark:text-neutral-500">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700 dark:text-neutral-400">Subtotal</span>
                                <span id="subtotal" class="font-medium text-black dark:text-neutral-100">$0</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700 dark:text-neutral-400">Margin (<span id="margin-label">10</span>%)</span>
                                <span id="margin-amount" class="font-medium text-emerald-600 dark:text-emerald-400">+$0</span>
                            </div>
                            <div class="flex items-center justify-between text-lg font-bold pt-2 border-t border-slate-100 dark:border-neutral-800">
                                <span class="text-black dark:text-neutral-100">Grand Total</span>
                                <span id="grand-total" class="text-black dark:text-neutral-100">$0</span>
                            </div>
                        </div>

                        <div class="pt-4 space-y-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-neutral-300">Notes (optional)</label>
                            <textarea name="notes" rows="3" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500" placeholder="Add any notes..."></textarea>
                        </div>

                        <div class="flex flex-col gap-2">
                            <button type="submit" id="submit-btn"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Create Quotation
                            </button>
                            <button type="button" id="clear-all" class="hidden inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:hover:bg-neutral-800">
                                Clear All
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                var items = [];
                var margin = 10;
                var searchTimer = null;

                function formatNumber(n) {
                    return Number(n).toLocaleString('en-US');
                }

                function updateUI() {
                    // Update item count
                    $('#item-count').text(items.length + ' item(s)');

                    // Toggle clear button
                    $('#clear-all').toggleClass('hidden', items.length === 0);

                    // Update submit button
                    if (items.length === 0) {
                        $('#submit-btn').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                    } else {
                        $('#submit-btn').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                    }

                    // Update hidden inputs
                    $('#items-input').val(JSON.stringify(items.map(function(i) { return { product_id: i.id, quantity: i.qty, price: i.price }; })));
                    $('#margin-input').val(margin);

                    // Calculate totals
                    var sub = 0;
                    $.each(items, function(_, item) {
                        sub += item.price * item.qty;
                    });
                    var total = sub * (1 + margin / 100);
                    var marginVal = total - sub;

                    $('#subtotal').text('$' + formatNumber(sub));
                    $('#margin-label').text(margin);
                    $('#margin-amount').text('+$' + formatNumber(marginVal));
                    $('#grand-total').text('$' + formatNumber(total));

                    // Render items
                    var container = $('#selected-items');
                    container.find('.item-row').remove();
                    container.find('.empty-state').toggleClass('hidden', items.length > 0);

                    $.each(items, function(idx, item) {
                        var row = $(
                            '<div class="item-row px-6 py-4 hover:bg-rose-50 dark:bg-neutral-900/50 dark:hover:bg-neutral-900/50 transition-colors" data-id="' + item.id + '">' +
                                '<div class="flex items-start gap-4">' +
                                    '<div class="flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100 dark:bg-neutral-900 shrink-0">' +
                                        '<svg class="w-7 h-7 text-gray-400 dark:text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>' +
                                    '</div>' +
                                    '<div class="flex-1 min-w-0">' +
                                        '<div class="flex items-start justify-between">' +
                                            '<div>' +
                                                '<p class="text-sm font-medium text-black dark:text-neutral-100">' + (item.name || item.description) + '</p>' +
                                                '<p class="text-xs font-mono text-gray-400 dark:text-neutral-500 mt-0.5">' + item.code + '</p>' +
                                            '</div>' +
                                            '<button type="button" class="remove-item shrink-0 text-gray-400 dark:text-neutral-500 hover:text-red-600 dark:hover:text-red-400 transition-colors">' +
                                                '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>' +
                                            '</button>' +
                                        '</div>' +
                                        '<div class="mt-2 grid grid-cols-2 gap-4 text-xs text-gray-700 dark:text-neutral-400">' +
                                            '<span>Qty: <input type="number" class="item-qty inline-block w-20 rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500" value="' + item.qty + '" min="1"></span>' +
                                            '<span>Unit Price: <span class="font-medium text-black dark:text-neutral-100">$' + formatNumber(item.price) + '</span></span>' +
                                        '</div>' +
                                        '<div class="mt-2 flex items-center justify-between">' +
                                            '<span class="text-xs text-gray-400 dark:text-neutral-500">Line Total:</span>' +
                                            '<span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">$' + formatNumber(item.price * item.qty) + '</span>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>'
                        );
                        container.append(row);
                    });

                    // Re-bind events for new items
                    container.find('.remove-item').off('click').on('click', function() {
                        var id = $(this).closest('.item-row').data('id');
                        items = items.filter(function(i) { return i.id !== id; });
                        updateUI();
                    });

                    container.find('.item-qty').off('change').on('change', function() {
                        var id = $(this).closest('.item-row').data('id');
                        var qty = parseInt($(this).val()) || 1;
                        if (qty < 1) qty = 1;
                        $(this).val(qty);
                        items = items.map(function(i) {
                            if (i.id === id) i.qty = qty;
                            return i;
                        });
                        updateUI();
                    });
                }

                // Margin controls
                $('#margin-slider, #margin-value').on('input change', function() {
                    var val = parseInt($(this).val()) || 10;
                    if (val < 0) val = 0;
                    if (val > 100) val = 100;
                    margin = val;
                    $('#margin-slider').val(val);
                    $('#margin-value').val(val);
                    updateUI();
                });

                // Product search
                $('#search-query').on('input', function() {
                    clearTimeout(searchTimer);
                    var q = $(this).val().trim();
                    if (q.length < 2) {
                        $('#search-results').addClass('hidden').empty();
                        return;
                    }
                    searchTimer = setTimeout(function() {
                        $.get('{{ route("client.products.search") }}?q=' + encodeURIComponent(q))
                            .done(function(data) {
                                var container = $('#search-results').removeClass('hidden').empty();
                                if (!data || data.length === 0) {
                                    container.html('<div class="p-4 text-sm text-gray-400 text-center">No products found</div>');
                                    return;
                                }
                                $.each(data, function(_, product) {
                                    var el = $(
                                        '<div class="flex items-center gap-4 p-4 hover:bg-rose-50 dark:hover:bg-neutral-900/50 transition-colors">' +
                                            '<div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-neutral-900">' +
                                                '<svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>' +
                                            '</div>' +
                                            '<div class="flex-1 min-w-0">' +
                                                '<p class="text-sm font-mono font-medium text-emerald-600 dark:text-emerald-400">' + product.product_code + '</p>' +
                                                '<p class="text-sm font-medium text-black dark:text-neutral-100 truncate">' + (product.description || product.product_description) + '</p>' +
                                                '<p class="text-xs text-gray-400 dark:text-neutral-500">$' + formatNumber(product.price || product.ddp_price) + '</p>' +
                                            '</div>' +
                                            '<button type="button" class="add-search-result inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:hover:bg-neutral-800">' +
                                                '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Add' +
                                            '</button>' +
                                        '</div>'
                                    );
                                    el.find('.add-search-result').on('click', function() {
                                        var exists = items.some(function(i) { return i.id == product.id; });
                                        if (!exists) {
                                            items.push({
                                                id: product.id,
                                                code: product.product_code,
                                                name: product.description || product.product_description,
                                                price: product.price || product.ddp_price,
                                                qty: 1
                                            });
                                            updateUI();
                                        }
                                        $('#search-query').val('').trigger('input');
                                    });
                                    container.append(el);
                                });
                            })
                            .fail(function() {
                                $('#search-results').addClass('hidden').empty();
                            });
                    }, 300);
                });

                // Click outside search results
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('#search-query, #search-results').length) {
                        $('#search-results').addClass('hidden');
                    }
                });

                // Clear all
                $('#clear-all').on('click', function() {
                    items = [];
                    updateUI();
                });
            })();
        </script>
    @endpush
</x-layouts.app>
