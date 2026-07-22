<x-layouts.app>
    <x-slot:title>Resellers - BWT</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Client Portal', 'url' => route('client.dashboard')], ['label' => 'Resellers']]" />

    <div class="space-y-6">
        <x-ui.hero title="Resellers" subtitle="Manage your Reseller accounts">
            <x-slot:actions>
                <a href="{{ route('client.reseller-users.create') }}" wire:navigate
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-400 hover:shadow-emerald-400/30 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Add Reseller
                </a>
            </x-slot:actions>
        </x-ui.hero>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 dark:border-neutral-800">
                <form method="GET" action="{{ route('client.reseller-users.index') }}" class="flex flex-1 items-center gap-3">
                    <div class="relative flex-1 max-w-md">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
                            class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pl-10 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Search</button>
                    @if(request('search'))
                        <a href="{{ route('client.reseller-users.index') }}" wire:navigateclass="text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-neutral-400">Clear</a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50 dark:border-neutral-800 dark:bg-neutral-900/50">
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-neutral-400">User</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-neutral-400">Email</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-neutral-400">Phone</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-neutral-400">Company</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-neutral-400">Role</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-neutral-400">Status</th>
                            <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-neutral-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-neutral-800">
                        @forelse($users ?? [] as $user)
                            <tr class="transition-colors hover:bg-slate-50/80 dark:hover:bg-neutral-800/30">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                          <a href="{{ route('client.reseller-users.show', $user) }}" wire:navigate title="View"
                                                class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-white">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100 dark:bg-neutral-800">
                                            {!! Avatar::create($user->name)->setDimension(36)->setFontSize(14)->toSvg() !!}
                                        </div>
                                           </a>
                                        <a href="{{ route('client.reseller-users.show', $user) }}" wire:navigate
                                        class="truncate text-sm font-medium text-slate-900 hover:text-emerald-600 dark:text-white dark:hover:text-emerald-400">
                                            {{ $user->name }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-600 dark:text-neutral-400">{{ $user->email }}</td>
                                <td class="px-5 py-4 text-slate-600 dark:text-neutral-400">{{ $user->phone ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    @if($user->userMeta && !empty($user->userMeta->metadata['company_name']))
                                        <span class="text-sm font-medium text-slate-700 dark:text-neutral-300">{{ $user->userMeta->metadata['company_name'] }}</span>
                                    @else
                                        <span class="text-sm text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-200 dark:bg-neutral-800 dark:text-neutral-300 dark:ring-neutral-700">{{ $role->name }}</span>
                                        @empty
                                            <span class="text-xs text-slate-400">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @if($user->status == 1)
                                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('client.reseller-users.show', $user) }}" wire:navigate title="View"
                                                class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-white">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            </a>
                                            <a href="{{ route('client.reseller-users.edit', $user) }}" wire:navigate title="Edit"
                                                class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-white">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                            </a>
                                            <form action="{{ route('client.reseller-users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete"
                                                    class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition-colors hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 dark:text-neutral-400 dark:hover:bg-red-900/20 dark:hover:text-red-400">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="flex flex-col items-center justify-center px-5 py-16 text-center">
                                        <svg class="h-12 w-12 text-slate-300 dark:text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                        <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">No Reseller Clients found</h3>
                                        <a href="{{ route('client.reseller-users.create') }}" wire:navigate class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                            Add Reseller User
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($users) && $users->hasPages())
                <div class="border-t border-slate-100 px-5 py-4 dark:border-neutral-800">
                    {{ $users->withQueryString()->links() }}
                </div>
            @elseif(isset($users) && $users->total() > 0)
                <div class="border-t border-slate-100 px-5 py-4 dark:border-neutral-800">
                    <p class="text-xs text-slate-400 dark:text-neutral-500">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
