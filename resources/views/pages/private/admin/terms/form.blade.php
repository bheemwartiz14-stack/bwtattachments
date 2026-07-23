@php $isEdit = isset($term); @endphp

<x-layouts.app>
    <x-slot:title>{{ $isEdit ? 'Edit Term' : 'Upload New Terms' }} - {{ $siteTitle }}</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Admin Portal', 'url' => route('admin.dashboard')],
        ['label' => 'Terms & Conditions', 'url' => route('admin.terms.index')],
        ['label' => $isEdit ? 'Edit Term' : 'Upload New Terms'],
    ]" />

    <div class="space-y-6">
        <x-ui.hero title="{{ $isEdit ? 'Edit Term' : 'Upload New Terms' }}" subtitle="{{ $isEdit ? 'Update term details and document' : 'Upload a new terms and conditions document' }}">
            <x-slot:icon>
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
            </x-slot:icon>
        </x-ui.hero>

        <form action="{{ $isEdit ? route('admin.terms.update', $term) : route('admin.terms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Term Details</h2>
                </div>
                <div class="p-8 space-y-6">
                    <x-forms.input
                        name="title"
                        label="Title"
                        placeholder="e.g. Terms & Conditions v1.0"
                        :value="old('title', $term->title ?? '')"
                    />

                    <x-forms.textarea
                        name="description"
                        label="Description (Optional)"
                        placeholder="Brief description of this terms document"
                        :value="old('description', $term->description ?? '')"
                    />

                    @if($isEdit && $term->getFirstMedia('file'))
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                            <p class="text-sm font-medium text-slate-700 dark:text-neutral-300">Current File</p>
                            <div class="mt-2 flex items-center gap-3">
                                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                <a href="{{ $term->getFirstMedia('file')->getUrl() }}" target="_blank" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                                    {{ $term->getFirstMedia('file')->file_name }}
                                </a>
                            </div>
                        </div>
                    @endif

                    <x-forms.input-file
                        name="file"
                        label="{{ $isEdit ? 'Replace Document (optional)' : 'Document File' }}"
                        accept=".pdf,.doc,.docx"
                        hint="PDF, DOC or DOCX only &middot; Max 10 MB{{ $isEdit ? ' &middot; Leave empty to keep current file' : '' }}"
                    />

                    @if($isEdit)
                        <x-forms.toggle
                            name="is_active"
                            label="Active"
                            :checked="old('is_active', $term->is_active ?? true)"
                        />
                    @endif
                </div>
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-8 py-5 dark:border-neutral-800 dark:bg-black/50">
                    <a href="{{ route('admin.terms.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                        {{ $isEdit ? 'Update Term' : 'Upload Terms' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
