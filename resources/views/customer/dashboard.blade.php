<x-layouts.app>
    <x-slot:title>Customer Dashboard - {{ $siteTitle }}</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Customer Portal']]" />

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">
        {{-- Welcome + Notifications row --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Welcome section --}}
            <div class="lg:col-span-2 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Welcome back, {{ $user->name ?? 'Client' }}</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">Here's what's happening with your account today.</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">customer</span>
                </div>
                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-neutral-500">Company</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-neutral-100">{{ $companyName }}</p>
                    </div>

                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-neutral-500">Last Login</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-neutral-100">{{ $lastLogin?->format('M d, Y H:i') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Notifications panel --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-950 dark:text-neutral-100">Notifications</h2>
                    <span class="inline-flex items-center justify-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">{{ $notifications->count() }}</span>
                </div>
                <div class="mt-4 divide-y divide-slate-100 dark:divide-neutral-800">
                    @forelse($notifications as $notification)
                        <div class="flex items-start gap-3 py-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg
                                {{ $notification['type'] === 'New PDF' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                {{ $notification['type'] === 'Price Update' ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' : '' }}">
                                @if($notification['icon'] === 'document-text')
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-slate-900 dark:text-neutral-100">{{ $notification['type'] }}</p>
                                <p class="truncate text-xs text-gray-500 dark:text-neutral-400">{{ $notification['message'] }}</p>
                            </div>
                            <span class="shrink-0 text-xs text-gray-400 dark:text-neutral-500">{{ $notification['time'] }}</span>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-gray-400 dark:text-neutral-500">No notifications yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
