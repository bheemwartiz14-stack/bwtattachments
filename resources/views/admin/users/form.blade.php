<x-layouts.app>
    <x-slot:title>{{ isset($user) ? 'Edit User' : 'Add User' }} - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => route('admin.dashboard')], ['label' => 'Users', 'url' => route('admin.users.index')], ['label' => isset($user) ? 'Edit' : 'New User']]" />

    <div class="mx-auto max-w-3xl">
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                </span>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">{{ isset($user) ? 'Edit User' : 'New User' }}</h1>
                    <p class="mt-0.5 text-sm text-slate-500">{{ isset($user) ? 'Update user details and permissions' : 'Create a new system user' }}</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200/50 dark:bg-emerald-900/30 dark:text-emerald-400 dark:ring-emerald-800/50">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Admin Access
            </span>
        </div>

        @if($errors->any())
            <div class="mb-8 overflow-hidden rounded-2xl border border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20" x-data="{ show: true }" x-show="show">
                <div class="flex items-start gap-3 p-4">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-red-800 dark:text-red-300">Please fix {{ $errors->count() > 1 ? 'these ' . $errors->count() . ' errors' : 'this error' }}:</p>
                            <button type="button" @@click="show = false" class="rounded-md p-1 text-red-400 transition-colors hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-800/50">&times;</button>
                        </div>
                        <ul class="mt-1.5 list-disc space-y-1 text-sm text-red-700 dark:text-red-300 [&_li]:ml-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
            @csrf
            @isset($user)
                @method('PUT')
            @endisset

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-5 dark:from-slate-800/50 dark:to-slate-900">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200/50 dark:bg-emerald-900/30 dark:text-emerald-400 dark:ring-emerald-800/50">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-950 dark:text-slate-100">Account Information</h2>
                            <p class="text-xs text-slate-500">Personal details and login credentials</p>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative mt-1.5">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="John Doe"
                                        class="block w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/15 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500 dark:focus:border-emerald-500 @error('name') border-red-300 dark:border-red-500 @enderror">
                                </div>
                                @error('name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-1">
                                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative mt-1.5">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                    </div>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="john@example.com"
                                        class="block w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/15 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500 dark:focus:border-emerald-500 @error('email') border-red-300 dark:border-red-500 @enderror">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @if(!isset($user))
                            <div class="sm:col-span-2" x-data="passwordGenerator()">
                                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ isset($user) ? 'New Password (leave blank to keep)' : 'Password' }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1.5 flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="text" id="password" name="password" x-model="password" readonly class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-3 pr-12 text-sm text-slate-900 shadow-sm transition-all dark:border-slate-600 dark:bg-slate-800/50 dark:text-gray-100 @error('password') border-red-300 dark:border-red-500 @enderror cursor-default select-all font-mono tracking-wider text-center text-base">
                                    </div>
                                </div>
                       
                                @error('password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-5 dark:from-slate-800/50 dark:to-slate-900">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-50 text-violet-600 ring-1 ring-violet-200/50 dark:bg-violet-900/30 dark:text-violet-400 dark:ring-violet-800/50">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-950 dark:text-slate-100">Organization &amp; Roles</h2>
                            <p class="text-xs text-slate-500">Company assignment and permission settings</p>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                            <div>
                                <label for="company_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Company</label>
                                <div class="relative mt-1.5">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                                    </div>
                                    <select id="company_id" name="company_id"
                                        class="block w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-8 text-sm text-slate-900 shadow-sm transition-all focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/15 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:focus:border-emerald-500">
                                        <option value="" class="text-slate-400">No company</option>
                                        @foreach($companies ?? [] as $company)
                                            <option value="{{ $company->id }}" @selected(old('company_id', $user->company_id ?? '') == $company->id)>{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>

                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                               <div class="mt-3" x-data="{ active: {{ old('status', $user->status ?? 1) == 1 ? 'true' : 'false' }} }">

                                <input type="hidden" name="status" :value="active ? 1 : 0">

                                 <label class="inline-flex cursor-pointer items-center gap-3">
                                    <button type="button"
                                            role="switch"
                                            :aria-checked="active"
                                            @click="active = !active"
                                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/15"
                                            :class="active ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600'">

                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition duration-200 ease-in-out"
                                            :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>

                                    <span class="text-sm text-slate-700 dark:text-slate-300"
                                        x-text="active ? 'Active' : 'Inactive'"></span>

                                </label>
                            </div>
                            </div>
                        </div>
                    </div>
                   <x-company-selector-modal :selected-company-id="$user->company_id ?? null"/>
                        @if(!isset($user))
                   <div class="px-6 py-5">
                    <label class="mb-3 block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Role <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="role"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm
                            focus:border-rose-400 focus:ring focus:ring-rose-200
                            dark:border-slate-700 dark:bg-slate-800 dark:text-white" >
                            <option value="">-- Select Role --</option>
                            @foreach($roles ?? [] as $role)
                                <option value="{{ $role->name }}"
                                    @selected(old('role', $userRole ?? '') == $role->name)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                </select>

                @error('role')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
</div>
                </div>
            </div>
    @endif
            <div class="sticky bottom-4 z-10 mt-8">
                <div class="rounded-2xl border border-slate-200/80 bg-white/90 px-6 py-4 shadow-lg shadow-slate-200/50 backdrop-blur-xl transition-all dark:border-slate-700/80 dark:bg-slate-900/90 dark:shadow-slate-900/50">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/15 active:scale-[0.98] dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:from-rose-700 hover:to-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-500/20 active:scale-[0.98]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ isset($user) ? 'Update User' : 'Create User' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function passwordGenerator() {
            return {
                password: '',
                generate() {
                    const upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    const lower = 'abcdefghijklmnopqrstuvwxyz';
                    const digits = '0123456789';
                    const special = '!@#$%^&*()_+{}[]<>?';
                    const all = upper + lower + digits + special;
                    let pwd = '';
                    pwd += upper.charAt(Math.floor(Math.random() * upper.length));
                    pwd += lower.charAt(Math.floor(Math.random() * lower.length));
                    pwd += digits.charAt(Math.floor(Math.random() * digits.length));
                    pwd += special.charAt(Math.floor(Math.random() * special.length));
                    for (let i = 0; i < 16; i++) {
                        pwd += all.charAt(Math.floor(Math.random() * all.length));
                    }
                    this.password = pwd.split('').sort(() => Math.random() - 0.5).join('');
                },
                init() {
                    if (!this.password) this.generate();
                }
            }
        }
    </script>
</x-layouts.app>
