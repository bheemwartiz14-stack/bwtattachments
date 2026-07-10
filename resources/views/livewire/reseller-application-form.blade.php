<div class="bg-white rounded-xl shadow-sm p-8">
    @if($success)
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 rounded-lg px-5 py-4 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Your application has been submitted successfully. We will contact you shortly.</span>
        </div>
    @endif

    <form wire:submit="submit" class="mt-8 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <x-forms.input name="company_name"  placeholder="Company name" :required="true" wire:model.blur="company_name" class="form-input" />
            <x-forms.input name="contact_person"  placeholder="Contact person" :required="true" wire:model.blur="contact_person" class="form-input"  />

            <x-forms.input name="address"  placeholder="Address" wire:model.blur="address" class="form-input" />
            <x-forms.input name="postal_code"  placeholder="Postal code" wire:model.blur="postal_code" class="form-input" />

            <x-forms.input name="place" placeholder="City" wire:model.blur="place"  class="form-input"/>
            <x-forms.input name="country"  placeholder="Country" wire:model.blur="country" class="form-input" />

            <x-forms.input name="email" type="email" placeholder="Email address" :required="true" wire:model.blur="email" class="form-input"/>
            <x-forms.input name="telephone"  type="tel" placeholder="Telephone number" wire:model.blur="telephone"  class="form-input"/>

            <x-forms.input name="website"  type="url" placeholder="Website" wire:model.blur="website" class="form-input" />
            <x-forms.input name="vat_number"  placeholder="VAT Number" wire:model.blur="vat_number" class="form-input" />

            <x-forms.input name="chamber_of_commerce" placeholder="Chamber of commerce number" wire:model.blur="chamber_of_commerce" class="form-input md:col-span-1" />
             <div class="hidden md:block"></div>
            <div class="sm:col-span-2">
                <x-forms.textarea name="additional_info" placeholder="Additional information" :rows="5" wire:model.blur="additional_info" class="form-input resize-none md:col-span-2" />
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-bwtblue hover:bg-bwtblue2 text-white font-semibold text-sm px-8 py-3 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                <span wire:loading.remove>Submit application</span>
                <span wire:loading>Sending...</span>
            </button>
        </div>
    </form>
</div>
