@php
    $activeTab = request()->query('tab', 'personal');
@endphp
<x-layouts.app>
    <x-slot:title>Profile Settings - {{ $siteTitle }}</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => $breadcrumbLabel, 'url' => route($breadcrumbRoute)],
        ['label' => 'Profile Settings'],
    ]" />

    <div class="space-y-6">
        <x-ui.hero title="Profile Settings" subtitle="Manage your personal information, company details, and security preferences">
            <x-slot:icon>
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </x-slot:icon>
        </x-ui.hero>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6">
            {{-- Sidebar Navigation --}}
            <div class="lg:w-56 shrink-0">
                <div class="lg:sticky lg:top-28 space-y-1">
                    <a href="{{ route($prefix . '.profile.edit', ['tab' => 'personal']) }}"
                        wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all {{ $activeTab === 'personal' ? 'bg-emerald-50 text-emerald-700 shadow-sm dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Personal Information
                    </a>
                    @if ($hasCompany)
                        <a href="{{ route($prefix . '.profile.edit', ['tab' => 'company']) }}"
                            wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all {{ $activeTab === 'company' ? 'bg-emerald-50 text-emerald-700 shadow-sm dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            Company Details
                        </a>
                    @endif
                    <a href="{{ route($prefix . '.profile.edit', ['tab' => 'pricing']) }}"
                       wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all {{ $activeTab === 'pricing' ? 'bg-emerald-50 text-emerald-700 shadow-sm dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pricing
                    </a>
                    <a href="{{ route($prefix . '.profile.edit', ['tab' => 'security']) }}"
                       wire:navigate  class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all {{ $activeTab === 'security' ? 'bg-emerald-50 text-emerald-700 shadow-sm dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        Security
                    </a>
                </div>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0 space-y-6">
                @if ($activeTab === 'personal')
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Personal Information</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">Update your name, phone number, and profile photo</p>
                        </div>
                        <form method="POST" action="{{ route($prefix . '.profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="p-8 space-y-8">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                                    <div class="shrink-0">
                                        <div class="relative">
                                            <div class="h-24 w-24 overflow-hidden rounded-full ring-4 ring-slate-100 dark:ring-neutral-800">
                                                @if ($avatarUrl)
                                                    <img src="{{ $avatarUrl }}" alt="Avatar" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-400 to-emerald-600 text-3xl font-bold text-white">
                                                        {!! Avatar::create($user->name)->setDimension(32)->setFontSize(14)->toSvg() !!}
                                                    </div>
                                                @endif
                                            </div>
                                            <label for="avatar_input"
                                                class="absolute bottom-0 right-0 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-white shadow-md ring-2 ring-white transition-all hover:bg-slate-50 hover:scale-110 dark:bg-neutral-800 dark:ring-neutral-900 dark:hover:bg-neutral-700">
                                                <svg class="h-4 w-4 text-slate-600 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </label>
                                            <input type="file" id="avatar_input" accept="image/jpeg,image/png,image/webp" class="hidden">
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-neutral-400 mt-0.5">{{ $user->email }}</p>
                                    </div>
                                    @if ($avatarUrl)
                                        <button type="button" id="remove-avatar-btn"
                                            class="shrink-0 rounded-lg border border-red-200 bg-white px-4 py-2 text-xs font-medium text-red-600 transition-colors hover:bg-red-50 dark:border-red-900/50 dark:bg-neutral-900 dark:text-red-400 dark:hover:bg-red-900/20">
                                            Remove
                                        </button>
                                    @endif
                                </div>

                                <hr class="border-slate-200 dark:border-neutral-800">

                                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                    <x-forms.input
                                        name="name"
                                        label="Full Name"
                                        :value="$user->name"
                                        :required="true"
                                        prepend='<svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>'
                                    />

                                    <x-forms.input
                                        name="email"
                                        label="Email"
                                        type="email"
                                        :value="$user->email"
                                        :required="true"
                                        :disabled="true"
                                    />

                                    <x-forms.phone
                                        name="phone"
                                        label="Phone"
                                        :value="$user->phone"
                                    />
                                </div>
                                <input type="hidden" name="avatar_temp" id="avatar_temp" value="">
                            </div>
                            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-8 py-5 dark:border-neutral-800 dark:bg-black/50">
                                <x-ui.button type="submit" variant="primary" label="Save Changes"
                                    icon='<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>' />
                            </div>
                        </form>
                    </div>

                @elseif($activeTab === 'company' && $hasCompany)
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Company Details</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">Your registered business information</p>
                        </div>
                        <div class="p-8">
                            <div class="flex flex-col sm:flex-row items-start gap-6">
                                @if ($logo ?? null)
                                    <div class="shrink-0">
                                        <img src="{{ $logo }}" alt="Company Logo"
                                            class="h-24 w-24 rounded-xl border border-slate-200 object-contain p-3 dark:border-neutral-700">
                                    </div>
                                @else
                                    <div class="shrink-0 flex h-24 w-24 items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900">
                                        <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1 space-y-3">
                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-neutral-500">Company Name</p>
                                        <p class="text-base font-semibold text-slate-900 dark:text-white mt-0.5">{{ $company_name ?? 'N/A' }}</p>
                                    </div>
                                    @if ($roleLabel)
                                        <div>
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                                {{ $roleLabel }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($activeTab === 'pricing')
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Pricing</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">Your wholesale pricing information</p>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-5 dark:border-neutral-800 dark:bg-neutral-900/50">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-neutral-500">Wholesale Price Percentage</p>
                                    <p class="mt-1.5 text-3xl font-bold text-slate-900 dark:text-white">{{ $commissionPercentage ?? 0 }}%</p>
                                    <p class="mt-1 text-xs text-slate-400 dark:text-neutral-500">Your commission/margin rate on all products</p>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($activeTab === 'security')
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Security</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">Update your password to keep your account secure</p>
                        </div>
                        <form method="POST" action="{{ route($prefix . '.profile.password') }}">
                            @csrf
                            @method('PUT')
                            <div class="p-8 space-y-6">
                                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                    <x-forms.password
                                        name="current_password"
                                        label="Current Password"
                                        placeholder="Enter current password"
                                        :required="true"
                                        :showGenerator="false"
                                        :showCopy="false"
                                    />

                                    <x-forms.password
                                        name="password"
                                        label="New Password"
                                        placeholder="Enter new password"
                                        :required="true"
                                        :showGenerator="true"
                                        :showCopy="true"
                                    />

                                    <x-forms.password
                                        name="password_confirmation"
                                        label="Confirm Password"
                                        placeholder="Confirm new password"
                                        :required="true"
                                        :showGenerator="false"
                                        :showToggle="true"
                                        :showCopy="false"
                                    />
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-8 py-5 dark:border-neutral-800 dark:bg-black/50">
                                <x-ui.button type="submit" variant="primary" label="Update Password"
                                    icon='<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>' />
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                var csrfToken = '{{ csrf_token() }}';
                var uploadUrl = '{{ route('upload-temp') }}';
                var tempInput = document.getElementById('avatar_temp');
                var fileInput = document.getElementById('avatar_input');
                var avatarContainer = document.querySelector('.h-24.w-24');

                if (fileInput) {
                    fileInput.addEventListener('change', function() {
                        var file = this.files[0];
                        if (!file) return;

                        var formData = new FormData();
                        formData.append('file', file);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', uploadUrl);
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                var data = JSON.parse(xhr.responseText);
                                tempInput.value = JSON.stringify(data);
                                var img = avatarContainer.querySelector('img');
                                if (img) {
                                    img.src = data.url;
                                } else {
                                    avatarContainer.innerHTML = '<img src="' + data.url + '" alt="Avatar" class="h-full w-full object-cover">';
                                }
                            }
                        };
                        xhr.send(formData);
                    });
                }

                var removeBtn = document.getElementById('remove-avatar-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '{{ route($prefix . ".profile.avatar.delete") }}');
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                        xhr.setRequestHeader('X-HTTP-Method-Override', 'DELETE');
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                window.location.reload();
                            }
                        };
                        xhr.send();
                    });
                }


            })();
        </script>
    @endpush
</x-layouts.app>
