<x-layouts.app>
    @php $isEdit = isset($category); @endphp

    <x-slot:title>{{ $isEdit ? 'Edit Category' : 'Add Category' }} - BWT</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
        ['label' => 'Categories', 'url' => route('admin.categories.index')],
        ['label' => $isEdit ? 'Edit Category' : 'New Category']
    ]" />

    <div class="space-y-6">
        <x-ui.hero title="{{ $isEdit ? 'Edit Category' : 'Add Category' }}" subtitle="{{ $isEdit ? 'Update category details' : 'Create a new category for the system' }}">
            <x-slot:icon>
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L12 3l9 4.5M4.5 10.5V19A1.5 1.5 0 006 20.5h12A1.5 1.5 0 0019.5 19v-8.5M9 20.5v-6h6v6" /></svg>
            </x-slot:icon>
        </x-ui.hero>

        {{-- Errors --}}
        <x-ui.error-alert />

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
                    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                        {{ $isEdit ? 'Update Category' : 'Create Category' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
