<x-layouts.app>
    <x-slot:title>{{ isset($subcategory) ? 'Edit Subcategory' : 'Add Subcategory' }} - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => route('admin.dashboard')], ['label' => 'Subcategories', 'url' => route('admin.subcategories.index')], ['label' => isset($subcategory) ? 'Edit' : 'New Subcategory']]" />

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">{{ isset($subcategory) ? 'Edit Subcategory' : 'New Subcategory' }}</h1>
                <p class="text-sm text-gray-700 mt-1">{{ isset($subcategory) ? 'Update subcategory details' : 'Create a new product subcategory' }}</p>
            </div>
        </div>

        @if($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/30 dark:border-red-800">
                <div class="flex items-center gap-2 text-sm text-red-800 dark:text-red-300 mb-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="font-medium">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($subcategory) ? route('admin.subcategories.update', $subcategory) : route('admin.subcategories.store') }}" method="POST">
            @csrf
            @isset($subcategory)
                @method('PUT')
            @endisset

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 space-y-5">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Parent Category <span class="text-red-500">*</span></label>
                    <select id="category_id" name="category_id" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 @error('category_id') border-red-300 @enderror">
                        <option value="">Select category</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $subcategory->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Subcategory Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $subcategory->name ?? '') }}" placeholder="e.g. General Purpose" x-on:input="slug = $el.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '')" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500 @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ slug: '{{ old('slug', $subcategory->slug ?? '') }}' }">
                    <label for="slug" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Slug</label>
                    <input type="text" id="slug" name="slug" x-model="slug" placeholder="auto-generated" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500 @error('slug') border-red-300 @enderror">
                    @error('slug')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Auto-generated from name. Change if needed.</p>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                    <select id="status" name="status" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                        <option value="active" @selected(old('status', $subcategory->status ?? 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', $subcategory->status ?? '') === 'inactive')>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.subcategories.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    {{ isset($subcategory) ? 'Update Subcategory' : 'Create Subcategory' }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
