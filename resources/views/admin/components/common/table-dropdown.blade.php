<div x-data="dropdown()" @click.away="close()">
    <div @click="toggle()" x-ref="button" class="cursor-pointer">
        {{ $button }}
    </div>

    <div class="z-50 fixed" x-ref="content" x-show="isOpen" x-cloak>
        <div class="p-2 bg-white border border-gray-200 rounded-2xl shadow-lg dark:border-gray-800 dark:bg-gray-dark w-40">
            <div class="space-y-1">
                {{ $content }}
            </div>
        </div>
    </div>
</div>

<script>
function dropdown() {
    return {
        isOpen: false,
        popperInstance: null,
        init() {
            // Check if Popper.js is available
            if (typeof createPopper === 'undefined') {
                console.warn('Popper.js not loaded! Dropdown will still work, but positioning may be off.');
                return;
            }

            this.popperInstance = createPopper(this.$refs.button, this.$refs.content, {
                placement: 'bottom-end',
                strategy: 'fixed',
                modifiers: [
                    { name: 'offset', options: { offset: [0, 4] } },
                ],
            });
        },
        toggle() {
            this.isOpen = !this.isOpen;

            if (this.popperInstance) {
                this.popperInstance.update();
            } else {
                // If Popper not loaded, just toggle manually
                this.$refs.content.style.position = 'absolute';
            }
        },
        close() {
            this.isOpen = false;
        }
    }
}
</script>
