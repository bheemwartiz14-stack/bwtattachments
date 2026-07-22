<x-layouts.app>
    <x-slot:title>{{ $user->name }} - Customers Client - BWT</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('reseller.dashboard')], ['label' => 'Retailer', 'url' => route('reseller.customer-users.index')], ['label' => $user->name]]" />
    @php
        $meta = $user->userMeta?->metadata ?? [];
        $margin = $user->userMargin?->margin_value ?? 0;
        $logoMedia = $user->getFirstMedia('customer_logo');
        $logoUrl = $logoMedia?->getUrl();
        $quotationsCount = $user->quotations->count();
        $draftCount = $user->quotations->where('status', 'draft')->count();
        $sentCount = $user->quotations->where('status', 'sent')->count();
        $approvedCount = $user->quotations->where('status', 'approved')->count();
    @endphp
    <div class="space-y-6">
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-6 shadow-sm dark:border-neutral-800 dark:from-neutral-900 dark:to-neutral-950">
            <div class="absolute right-0 top-0 h-32 w-64 translate-x-8 -translate-y-8 rounded-full bg-emerald-500/5 blur-3xl dark:bg-emerald-500/10"></div>
            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-neutral-800 dark:ring-neutral-700">
                        {!! Avatar::create($user->name)->setDimension(64)->setFontSize(24)->toSvg() !!}
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">{{ $user->name }}</h1>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Active
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">{{ $meta['company_name'] ?? 'No company' }} &middot; {{ $user->email }}</p>
                        <div class="mt-3 flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-neutral-800 dark:text-neutral-400">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                {{ $user->roles->first()?->name ?? 'Retailer' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-neutral-800 dark:text-neutral-400">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Member since {{ $user->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('reseller.customer-users.edit', $user) }}" wire:navigate
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-500 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                    Edit User
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Left column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Company Details --}}
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-900/20">
                            <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21h10.5" /></svg>
                        </div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Company Details</h2>
                    </div>
                    <div class="px-6 py-5">
                        <dl class="grid grid-cols-1 gap-x-8 gap-y-5 sm:grid-cols-2">
                            @foreach([
                                ['Company Name', $meta['company_name'] ?? '—', true],
                                ['VAT Number', $meta['vat_number'] ?? '—', true],
                                ['Address', $meta['address'] ?? '—', false],
                                ['Postal Code', $meta['postal_code'] ?? '—', false],
                                ['City', $meta['city'] ?? '—', false],
                                ['Country', $meta['country'] ?? '—', false],
                            ] as [$label, $val, $semibold])
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-neutral-500">{{ $label }}</dt>
                                    <dd class="mt-1 text-sm {{ $semibold ? 'font-semibold text-slate-900' : 'text-slate-700' }} dark:text-neutral-100">{{ $val }}</dd>
                                </div>
                            @endforeach
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-neutral-500">Website</dt>
                                <dd class="mt-1 text-sm">
                                    @if(!empty($meta['website']))
                                        <a href="{{ $meta['website'] }}" wire:navigate target="_blank" class="inline-flex items-center gap-1.5 font-medium text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300">
                                            {{ $meta['website'] }}
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                        </a>
                                    @else
                                        <span class="text-slate-500 dark:text-neutral-400">—</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Account Details --}}
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-900/20">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        </div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Account Details</h2>
                    </div>
                    <div class="px-6 py-5">
                        <dl class="grid grid-cols-1 gap-x-8 gap-y-5 sm:grid-cols-2">
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-neutral-500">Full Name</dt>
                                <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-neutral-100">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-neutral-500">Email</dt>
                                <dd class="mt-1 text-sm text-slate-700 dark:text-neutral-100">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-neutral-500">Phone</dt>
                                <dd class="mt-1 text-sm text-slate-700 dark:text-neutral-100">{{ $user->phone ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-neutral-500">Username</dt>
                                <dd class="mt-1 text-sm font-mono font-medium text-slate-900 dark:text-neutral-100">{{ $user->username }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                 @php
                                    $decryptedPass = \App\Helpers\PasswordHelper::isEncrypted($meta['plain_password'])
                                        ? \App\Helpers\PasswordHelper::decrypt($meta['plain_password'])
                                        : $meta['plain_password'];
                                @endphp
                                <x-forms.password
                                    name="password"
                                    label="Password"
                                    :value="$decryptedPass"
                                    readonly
                                    :showGenerator="false"
                                    :showToggle="true"
                                    :showCopy="true"
                                    wrapperClass="w-full"
                                />
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-neutral-500">Role</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        {{ $user->roles->first()?->name ?? 'Retailer' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-neutral-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-neutral-500">Member Since</dt>
                                <dd class="mt-1 text-sm text-slate-700 dark:text-neutral-100">{{ $user->created_at->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Recent Quotations --}}
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-900/20">
                                <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Recent Quotations</h2>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-neutral-800 dark:text-neutral-400">
                            {{ $quotationsCount }} total
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/50 dark:border-neutral-800 dark:bg-neutral-900/50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-neutral-500">Quotation #</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-neutral-500">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-neutral-500">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-neutral-500">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-neutral-800">
                                @forelse($user->quotations as $quotation)
                                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-neutral-800/50">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-medium text-slate-700 dark:text-neutral-300">{{ $quotation->quotation_number }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-neutral-400">{{ $quotation->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                {{ $quotation->status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                                                {{ $quotation->status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                                                {{ $quotation->status === 'draft' ? 'bg-slate-100 text-slate-800 dark:bg-neutral-900 dark:text-neutral-300' : '' }}
                                                {{ $quotation->status === 'sent' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : '' }}
                                                {{ $quotation->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : '' }}">
                                                {{ ucfirst($quotation->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format($quotation->items->sum(fn($i) => $i->price * $i->quantity) * (1 + ($quotation->margin_percentage ?: 0) / 100), 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-400 dark:text-neutral-500">No quotations yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right column --}}
            <div class="space-y-6">
                {{-- Logo --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-sky-50 to-sky-100 dark:from-sky-900/30 dark:to-sky-900/20">
                            <svg class="h-4 w-4 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" /></svg>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Company Logo</h3>
                    </div>
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $meta['company_name'] ?? 'Logo' }}" class="max-h-32 w-full rounded-xl border border-slate-100 object-contain p-4 dark:border-neutral-700">
                    @else
                        <div class="flex h-32 items-center justify-center rounded-xl border-2 border-dashed border-slate-200 dark:border-neutral-700">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-slate-300 dark:text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" /></svg>
                                <p class="mt-2 text-xs text-slate-400 dark:text-neutral-500">No logo uploaded</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Commission --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-900/20">
                            @svg('heroicon-o-percent-badge', 'h-4 w-4 text-emerald-600 dark:text-emerald-400')
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Default Commission</h3>
                    </div>
                    <div class="flex items-center gap-4 rounded-xl bg-slate-50 p-4 dark:bg-neutral-800">
                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white shadow-sm dark:bg-neutral-800/80">
                            <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400">%</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ number_format($margin, 2) }}%</p>
                            <p class="text-xs text-slate-500 dark:text-neutral-300">Default margin on all products</p>
                        </div>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/30 dark:to-indigo-900/20">
                            <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Quick Stats</h3>
                    </div>
                    <div class="space-y-3">
                        @foreach([
                            ['Total Quotations', $quotationsCount, 'text-slate-900', 'dark:text-white'],
                            ['Drafts', $draftCount, 'text-amber-600', 'dark:text-amber-300'],
                            ['Sent', $sentCount, 'text-blue-600', 'dark:text-blue-300'],
                            ['Approved', $approvedCount, 'text-emerald-600', 'dark:text-emerald-300'],
                        ] as [$label, $val, $color, $darkColor])
                            <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-2.5 dark:bg-neutral-800">
                                <span class="text-sm text-slate-600 dark:text-neutral-300">{{ $label }}</span>
                                <span class="text-sm font-semibold {{ $color }} {{ $darkColor }}">{{ $val }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
