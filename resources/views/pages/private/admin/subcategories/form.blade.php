<x-layouts.app>
    @php $isEdit = isset($subcategory); @endphp

    <x-slot:title>{{ $isEdit ? 'Edit Subcategory' : 'Add Subcategory' }} - BWT</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Admin Portal', 'url' => route('admin.dashboard')],
        ['label' => 'Subcategories', 'url' => route('admin.subcategories.index')],
        ['label' => $isEdit ? 'Edit Subcategory' : 'New Subcategory']
    ]" />

    <div class="space-y-6">
        <x-ui.hero title="{{ $isEdit ? 'Edit Subcategory' : 'Add Subcategory' }}" subtitle="{{ $isEdit ? 'Update subcategory details' : 'Create a new subcategory' }}">
            <x-slot:icon>
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
            </x-slot:icon>
        </x-ui.hero>

        {{-- Errors --}}
       

        {{-- Form --}}
        <form action="{{ $isEdit ? route('admin.subcategories.update', $subcategory) : route('admin.subcategories.store') }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Subcategory Details</h2>
                </div>
                <div class="p-8 space-y-6">
                    <x-forms.select
                        name="category_id"
                        label="Parent Category"
                        :options="$categories"
                        :value="old('category_id', $subcategory->category_id ?? '')"
                        required
                    />
                    <x-forms.input
                        name="name"
                        label="Subcategory Name"
                        placeholder="e.g. General Purpose"
                        :value="old('name', $subcategory->name ?? '')"
                        required
                    />
                </div>
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-8 py-5 dark:border-neutral-800 dark:bg-black/50">
                    <a href="{{ route('admin.subcategories.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</a>
                    <x-ui.button type="submit" variant="primary" label="{{ $isEdit ? 'Update Subcategory' : 'Create Subcategory' }}" />
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
