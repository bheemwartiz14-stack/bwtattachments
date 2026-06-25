@php $isEdit = !empty($price); @endphp

<x-layouts.app>
    <x-slot:title>{{ $isEdit ? 'Edit' : 'Add' }} Wholesale Purchase Price - Attachment Portal</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
        ['label' => 'Product Pricing', 'url' => route('admin.product-pricing.index')],
        ['label' => $isEdit ? 'Edit' : 'New']
    ]" />

    <div class="space-y-8">
        <x-ui.hero title="{{ $isEdit ? 'Edit Wholesale Purchase Price' : 'Add Wholesale Purchase Price' }}" subtitle="{{ $isEdit ? 'Update the wholesale purchase price' : 'Set a wholesale purchase price for a product and client' }}">
            <x-slot:icon>
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </x-slot:icon>
        </x-ui.hero>

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 dark:border-red-900/50 dark:bg-red-900/20">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 dark:text-red-300">Please fix {{ $errors->count() > 1 ? 'these ' . $errors->count() . ' errors' : 'this error' }}:</p>
                        <ul class="mt-1.5 list-disc space-y-1 text-sm text-red-700 dark:text-red-400 [&_li]:ml-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ $isEdit ? route('admin.product-pricing.update', $price) : route('admin.product-pricing.store') }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex items-center gap-3 border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/30">
                        <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Pricing Details</h2>
                        <p class="text-xs text-slate-500 dark:text-neutral-400">Select product, client, and set the purchase price</p>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 lg:grid-cols-2">
                        <div class="lg:col-span-2">
                            <x-forms.select name="product_id" label="Product" required
                                :options="$products ?? []"
                                :value="$isEdit ? $price->product_id : ''"
                                placeholder="Select a product"
                                :disabled="$isEdit" />
                        </div>

                        <div class="lg:col-span-2">
                            <x-forms.select name="user_id" label="Wholesale Client" required
                                :options="$wholesaleUsers ?? []"
                                :value="$isEdit ? $price->user_id : ''"
                                placeholder="Select a wholesale client"
                                :disabled="$isEdit" />
                        </div>

                        <x-forms.currency name="price" label="Wholesale Purchase Price" required
                            :value="$isEdit ? $price->price : ''"
                            placeholder="0.00" />

                        <x-forms.input name="margin" label="Margin (%)"
                            type="number" step="0.01" min="0" max="999.99"
                            :value="$isEdit ? $price->margin : ''"
                            placeholder="Optional"
                            hint="Markup percentage over DDP price" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 rounded-2xl border border-slate-200 bg-white px-8 py-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <a href="{{ route('admin.product-pricing.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2.5 rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ $isEdit ? 'Update Price' : 'Save Price' }}
                </button>
            </div>
        </form>
    </div>


</x-layouts.app>
