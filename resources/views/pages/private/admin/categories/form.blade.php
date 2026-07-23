<x-layouts.app>
    @php $isEdit = isset($category); @endphp

    <x-slot:title>{{ $isEdit ? 'Edit Category' : 'Add Category' }} - BWT</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Admin Portal', 'url' => route('admin.dashboard')],
        ['label' => 'Manage Categories', 'url' => route('admin.categories.index')],
        ['label' => $isEdit ? 'Edit Category' : 'New Category']
    ]" />

    <div class="space-y-6">
        <x-ui.hero title="{{ $isEdit ? 'Edit Category' : 'Add Category' }}" icon="heroicon-o-rectangle-stack" />

        {{-- Errors --}}
       

        {{-- Form --}}
        <form action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Category Details</h2>
                </div>
                <div class="p-8 space-y-6">
                    <x-forms.input
                        name="name"
                        label="Category Name"
                        placeholder="Enter category name"
                        :value="old('name', $category->name ?? '')"
                    />
                </div>
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-8 py-5 dark:border-neutral-800 dark:bg-black/50">
                    <a href="{{ route('admin.categories.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</a>
                    <x-ui.button type="submit" variant="primary" label="{{ $isEdit ? 'Update Category' : 'Create Category' }}" />
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
