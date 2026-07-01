<div>
    @if($success)
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 rounded-lg px-5 py-4 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Your message has been sent successfully. We will get back to you shortly.</span>
        </div>
    @endif

    <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a message</h2>

    <form wire:submit="submit" class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                <input
                    id="name"
                    type="text"
                    wire:model.blur="name"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('name') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="John Doe"
                />
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input
                    id="email"
                    type="email"
                    wire:model.blur="email"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('email') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                    placeholder="john@example.com"
                />
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
            <input
                id="subject"
                type="text"
                wire:model.blur="subject"
                class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all @error('subject') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                placeholder="Product inquiry"
            />
            @error('subject') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
            <textarea
                id="message"
                wire:model.blur="message"
                rows="5"
                class="w-full px-4 py-2.5 border rounded-lg text-sm outline-none transition-all resize-none @error('message') border-red-400 ring-2 ring-red-200 @else border-gray-300 focus:ring-2 focus:ring-bwtblue focus:border-bwtblue @enderror"
                placeholder="Tell us about your inquiry..."></textarea>
            @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-bwtblue hover:bg-bwtblue2 text-white font-semibold text-sm px-8 py-3 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
            <span wire:loading.remove>Send Message</span>
            <span wire:loading>Sending...</span>
        </button>
    </form>
</div>
