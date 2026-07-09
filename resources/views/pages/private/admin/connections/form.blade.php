<x-layouts.app>
    @php $isEdit = isset($connection); @endphp

    <x-slot:title>{{ $isEdit ? 'Edit Connection' : 'Add Connection' }} - BWT</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
        ['label' => 'Connections', 'url' => route('admin.connections.index')],
        ['label' => $isEdit ? 'Edit Connection' : 'New Connection']
    ]" />

    <div class="space-y-6">
        <x-ui.hero title="{{ $isEdit ? 'Edit Connection' : 'Add Connection' }}" subtitle="{{ $isEdit ? 'Update connection type' : 'Create a new connection type' }}">
            <x-slot:icon>
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
            </x-slot:icon>
        </x-ui.hero>

        {{-- Errors --}}
       

        {{-- Form --}}
        <form action="{{ $isEdit ? route('admin.connections.update', $connection) : route('admin.connections.store') }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Connection Details</h2>
                </div>
                <div class="p-8 space-y-6">
                    <x-forms.input
                        name="name"
                        label="Connection Name"
                        placeholder="e.g. Pin-on"
                        :value="old('name', $connection->name ?? '')"
                        required
                    />
                </div>
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-8 py-5 dark:border-neutral-800 dark:bg-black/50">
                    <a href="{{ route('admin.connections.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                        {{ $isEdit ? 'Update Connection' : 'Create Connection' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
