<div>
    @if($success)
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 rounded-lg px-5 py-4 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Your message has been sent successfully. We will get back to you shortly.</span>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <x-forms.input name="name" label="Full Name" placeholder="John Doe" :required="true" wire:model.blur="name" class="form-input" wrapperClass="form-input-wrapper" />
            <x-forms.input name="email" label="Email" type="email" placeholder="john@example.com" :required="true" wire:model.blur="email" class="form-input " wrapperClass="form-input-wrapper" />
            <x-forms.input name="company" label="Company name" placeholder="Your company" :required="false" wire:model.blur="company" class="form-input" wrapperClass="form-input-wrapper" />
            <x-forms.input name="phone" label="Tel. number" type="tel" placeholder="+1 (555) 123-4567" :required="false" wire:model.blur="phone" class="form-input" wrapperClass="form-input-wrapper" />
        </div>

        <x-forms.input name="subject" label="Subject" placeholder="Product inquiry" :required="true" wire:model.blur="subject" class="form-input" wrapperClass="form-input-wrapper" />

        <x-forms.textarea name="message" label="Message" placeholder="Tell us about your inquiry..." :rows="5" :required="true" wire:model.blur="message" class="form-input" wrapperClass="form-input-wrapper" />

        <div class="flex justify-end">
            <x-ui.button type="submit" variant="brand" wire:loading.attr="disabled">
                <span wire:loading.remove>Send Message</span>
                <span wire:loading>Sending...</span>
            </x-ui.button>
        </div>
    </form>
</div>
