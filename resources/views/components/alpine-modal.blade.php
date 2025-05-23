@props(['name', 'title' => null, 'maxWidth' => '2xl', 'focusable' => false])

@php
$maxWidthClasses = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    x-data="{
        show: false,
        focusable: @json($focusable),
        focusables() {
            // All focusable element types...
            let selector = 'a, button, input:not([type=hidden]), textarea, select, details, [tabindex]:not([tabindex="-1"])'
            return [...$el.querySelectorAll(selector)]
                // All non-disabled elements...
                .filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },

        init() {
            this.$watch('show', value => {
                if (value && this.focusable) {
                    // Focus first element on show
                    this.$nextTick(() => this.firstFocusable()?.focus())
                }
                // Lock scroll when modal is open
                if (value) {
                    document.body.classList.add('overflow-y-hidden');
                } else {
                    document.body.classList.remove('overflow-y-hidden');
                }
            })
        },

        openModal(event) {
            if (event.detail === '{{ $name }}') {
                this.show = true;
            }
        },
        closeModal() {
            this.show = false;
        },
        handleKeydown(event) {
            if (event.key === 'Escape') {
                this.show = false;
                return;
            }
            if (event.key === 'Tab') {
                event.preventDefault();
                if (event.shiftKey) {
                    this.prevFocusable().focus();
                } else {
                    this.nextFocusable().focus();
                }
            }
        }
    }"
    x-init="init()"
    x-on:open-modal.window="openModal($event)"
    x-on:close.stop="closeModal()"
    x-on:keydown.window="handleKeydown($event)"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;" {{-- Hide initially to prevent flash --}}
    aria-modal="true"
    role="dialog"
    tabindex="-1" {{-- Make the modal container focusable initially --}}
>
    {{-- Background Overlay --}}
    <div x-show="show" class="fixed inset-0 transform transition-all" x-on:click="show = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
    </div>

    {{-- Modal Content --}}
    <div x-show="show" class="mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidthClasses }} sm:mx-auto" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        {{-- Optional Title --}}
        @if ($title)
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $title }}
            </h3>
        </div>
        @endif

        {{-- Main Content Slot --}}
        <div class="px-6 py-4">
            {{ $slot }}
        </div>

    </div>
</div>
