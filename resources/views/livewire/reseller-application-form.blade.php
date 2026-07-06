<div class="bg-white rounded-xl shadow-sm p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Apply to Become a Reseller</h2>
    <p class="text-gray-600 text-sm mb-6">Please fill in the required fields and we will contact you.</p>

    @if($success)
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 rounded-lg px-5 py-4 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Your application has been submitted successfully. We will contact you shortly.</span>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1.5">Company name *</label>
                <input id="company_name" type="text" wire:model.blur="company_name"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('company_name') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="Your company name">
                @error('company_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1.5">Contact person *</label>
                <input id="contact_person" type="text" wire:model.blur="contact_person"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('contact_person') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="Full name">
                @error('contact_person') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Address *</label>
                <input id="address" type="text" wire:model.blur="address"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('address') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="Street and number">
                @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1.5">Postal code *</label>
                <input id="postal_code" type="text" wire:model.blur="postal_code"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('postal_code') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="e.g. 1234 AB">
                @error('postal_code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="place" class="block text-sm font-medium text-gray-700 mb-1.5">Place *</label>
                <input id="place" type="text" wire:model.blur="place"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('place') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="City">
                @error('place') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700 mb-1.5">Country *</label>
                <input id="country" type="text" wire:model.blur="country"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('country') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="Country">
                @error('country') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1.5">Telephone *</label>
                <input id="telephone" type="text" wire:model.blur="telephone"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('telephone') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="+31 (0) ...">
                @error('telephone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                <input id="email" type="email" wire:model.blur="email"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('email') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="name@company.com">
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="website" class="block text-sm font-medium text-gray-700 mb-1.5">Website</label>
                <input id="website" type="text" wire:model.blur="website"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('website') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="https://">
                @error('website') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit" class="bg-bwtblue hover:bg-bwtblue2 text-white font-semibold text-sm px-8 py-3 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
            <span wire:loading.remove>Submit Application</span>
            <span wire:loading>Sending...</span>
        </button>
    </form>
</div>
