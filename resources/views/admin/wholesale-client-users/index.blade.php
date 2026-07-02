<x-layouts.app>
    <x-slot:title>Wholesale Clients - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => route('admin.dashboard')], ['label' => 'Wholesale Clients']]" />

    <div class="space-y-6">
        <x-ui.hero title="Wholesale Clients" subtitle="Manage your wholesale client user accounts">
            <x-slot:actions>
                <a href="{{ route('admin.wholesale-client-users.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-400 hover:shadow-emerald-400/30 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Add Client User
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
                <form method="GET" action="{{ route('admin.wholesale-client-users.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[200px] max-w-md">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
                            class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pl-10 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                    </div>
                    <select name="role"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                        <option value="">All Roles</option>
                        <option value="Wholesale Client" {{ request('role') === 'Wholesale Client' ? 'selected' : '' }}>Wholesale Client</option>
                        <option value="Retailer" {{ request('role') === 'Retailer' ? 'selected' : '' }}>Retailer</option>
                        <option value="Super Admin" {{ request('role') === 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Search</button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('admin.wholesale-client-users.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-neutral-400">Clear</a>
                    @endif
                </form>
            </div>

            @php
                $roleConfig = [
                    'Wholesale Client' => ['icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'badge' => 'bg-emerald-500/10 text-emerald-700 ring-emerald-500/20 dark:bg-emerald-500/20 dark:text-emerald-300 dark:ring-emerald-500/30', 'pill' => 'bg-emerald-100 text-emerald-800 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-300 dark:ring-emerald-700'],
                    'Retailer' => ['icon' => 'M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72l1.189-1.19A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72M6.75 18h3.75a.75.75 0 00.75-.75V13.5h-4.5v3.75c0 .414.336.75.75.75zm6 0h3.75a.75.75 0 00.75-.75V13.5h-4.5v3.75c0 .414.336.75.75.75z', 'bg' => 'bg-blue-50 dark:bg-blue-900/20', 'badge' => 'bg-blue-500/10 text-blue-700 ring-blue-500/20 dark:bg-blue-500/20 dark:text-blue-300 dark:ring-blue-500/30', 'pill' => 'bg-blue-100 text-blue-800 ring-blue-200 dark:bg-blue-900/40 dark:text-blue-300 dark:ring-blue-700'],
                    'Super Admin' => ['icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'bg' => 'bg-purple-50 dark:bg-purple-900/20', 'badge' => 'bg-purple-500/10 text-purple-700 ring-purple-500/20 dark:bg-purple-500/20 dark:text-purple-300 dark:ring-purple-500/30', 'pill' => 'bg-purple-100 text-purple-800 ring-purple-200 dark:bg-purple-900/40 dark:text-purple-300 dark:ring-purple-700'],
                ];
                $roleOrder = ['Wholesale Client', 'Retailer', 'Super Admin'];
                $grouped = collect($roleOrder)->mapWithKeys(fn($role) => [$role => collect()]);
                foreach ($users ?? [] as $user) {
                    $role = $user->roles->first()?->name ?? 'Other';
                    if (in_array($role, $roleOrder)) {
                        $grouped[$role]->push($user);
                    }
                }
                $grouped = $grouped->filter(fn($g) => $g->isNotEmpty());
            @endphp

            @forelse($grouped as $roleName => $roleUsers)
                @php $cfg = $roleConfig[$roleName] ?? $roleConfig['Wholesale Client']; @endphp
                <div class="border-b border-slate-100 last:border-b-0 dark:border-neutral-800">
                    <div class="flex items-center gap-3 px-5 py-4 {{ $cfg['bg'] }}">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $cfg['badge'] }} ring-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $cfg['icon'] }}"/></svg>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ $roleName }}</h3>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $cfg['pill'] }} ring-1">{{ $roleUsers->count() }}</span>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-neutral-800">
                        @foreach($roleUsers as $user)
                            @php $meta = $user->userMeta?->metadata ?? []; @endphp
                            <div class="flex items-center gap-4 px-5 py-4 transition-colors hover:bg-slate-50/80 dark:hover:bg-neutral-800/30">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200 dark:bg-neutral-800 dark:ring-neutral-700">
                                    {!! Avatar::create($user->name)->setDimension(40)->setFontSize(16)->toSvg() !!}
                                </div>
                                <div class="flex min-w-0 flex-1 items-center gap-4">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                         @if($roleName === 'Wholesale Client')
    <a href="{{ route('admin.wholesale-client-users.show', $user) }}"
       class="truncate text-sm font-medium text-slate-900 hover:text-emerald-600 dark:text-white dark:hover:text-emerald-400">
        {{ $user->name }}
    </a>
@else
    {{ $user->name }}
@endif
                                        </div>
                                        <p class="truncate text-xs text-slate-500 dark:text-neutral-400">{{ $user->email }}</p>
                                    </div>
                                    <div class="hidden sm:block min-w-0 flex-1">
                                        <p class="truncate text-sm text-slate-700 dark:text-neutral-300">{{ $user->phone ?? '—' }}</p>
                                    </div>
                                    <div class="hidden md:block min-w-0 flex-1">
                                        @if(!empty($meta['wholesale_company_name']))
                                            <p class="truncate text-sm font-medium text-slate-700 dark:text-neutral-300">{{ $meta['wholesale_company_name'] }}</p>
                                        @else
                                            <p class="text-sm text-slate-400">—</p>
                                        @endif
                                    </div>
                                    <div class="hidden lg:flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-200 dark:bg-neutral-800 dark:text-neutral-300 dark:ring-neutral-700">{{ $role->name }}</span>
                                        @empty
                                            <span class="text-xs text-slate-400">—</span>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center gap-1">

                                      @if($roleName === 'Wholesale Client')
                                    <a href="{{ route('admin.wholesale-client-users.edit', $user) }}" title="Edit"
                                        class="inline-flex items-center justify-center rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 dark:text-neutral-500 dark:hover:bg-neutral-800 dark:hover:text-white">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    </a>
                                    <form action="{{ route('admin.wholesale-client-users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-slate-400 transition-colors hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 dark:text-neutral-500 dark:hover:bg-red-900/20 dark:hover:text-red-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </form>
                                        @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center px-5 py-16 text-center">
                    <svg class="h-12 w-12 text-slate-300 dark:text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">No Users found</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">Get started by creating a new user.</p>
                    <a href="{{ route('admin.wholesale-client-users.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        Add Client User
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
