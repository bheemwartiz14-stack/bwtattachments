<div id="toast"
    class="fixed bottom-4 right-4 z-50 hidden items-center gap-3 rounded-xl border border-slate-100 bg-white px-5 py-3 shadow-lg dark:border-neutral-800 dark:bg-neutral-950"
    role="alert">
    <svg class="w-5 h-5 text-emerald-500 shrink-0 hidden" data-toast-icon="success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <svg class="w-5 h-5 text-red-500 shrink-0 hidden" data-toast-icon="error" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <p class="text-sm text-black dark:text-neutral-100" data-toast-message></p>
    <button data-toast-close class="shrink-0 text-gray-400 hover:text-gray-700 dark:text-neutral-500 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
