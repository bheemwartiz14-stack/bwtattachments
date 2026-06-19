<x-layouts.app>
    <x-slot:title>{{ isset($company) ? 'Edit Company' : 'Add Company' }} - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => route('admin.dashboard')], ['label' => 'Companies', 'url' => route('admin.companies.index')], ['label' => isset($company) ? 'Edit' : 'New Company']]" />

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">{{ isset($company) ? 'Edit Company' : 'New Company' }}</h1>
                <p class="text-sm text-gray-700 mt-1">{{ isset($company) ? 'Update company details' : 'Register a new wholesale company' }}</p>
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

        <form action="{{ isset($company) ? route('admin.companies.update', $company) : route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @isset($company)
                @method('PUT')
            @endisset

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 space-y-5">
                <div>
                    <label for="company_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $company->company_name ?? '') }}" placeholder="e.g. ABC Construction Ltd" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500 @error('company_name') border-red-300 @enderror">
                    @error('company_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="logo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Company Logo</label>
                    <input type="file" id="logo" name="logo" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 dark:file:bg-rose-900/30 dark:file:text-rose-300 dark:hover:file:bg-rose-900/50">
                    @error('logo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @if(isset($company) && $company->logo)
                        <div class="mt-3 flex items-center gap-3">
                            <img src="{{ Storage::url($company->logo) }}" alt="" class="w-16 h-16 rounded-xl object-cover border border-slate-200 dark:border-slate-700">
                            <span class="text-xs text-gray-400">Current logo</span>
                        </div>
                    @endif
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                    <select id="status" name="status" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                        <option value="1" @selected(old('status', $company->status ?? '1') == '1')>Active</option>
                        <option value="0" @selected(old('status', $company->status ?? '') == '0')>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    {{ isset($company) ? 'Update Company' : 'Create Company' }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
