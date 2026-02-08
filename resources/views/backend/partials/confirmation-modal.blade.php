<div x-data="{ 
    open: false, 
    title: '', 
    message: '', 
    formToSubmit: null,
    callback: null,
    confirmText: '{{ __('messages.confirm') }}',
    cancelText: '{{ __('messages.cancel') }}',
    confirmButtonClass: 'btn-danger',
    
    show(options) {
        this.title = options.title || '{{ __('messages.are_you_sure') }}';
        this.message = options.message || '{{ __('messages.revert_action_warning') }}';
        this.confirmText = options.confirmText || '{{ __('messages.confirm') }}';
        this.cancelText = options.cancelText || '{{ __('messages.cancel') }}';
        this.confirmButtonClass = options.confirmButtonClass || 'btn-danger';
        this.formToSubmit = options.form || null;
        this.callback = options.callback || null;
        this.open = true;
        
        // Preline overlay toggle
        const el = document.querySelector('#confirmation-modal');
        if (typeof HSOverlay !== 'undefined') {
            HSOverlay.open(el);
        }
    },
    
    confirm() {
        if (this.formToSubmit) {
            this.formToSubmit.submit();
        } else if (this.callback) {
            this.callback();
        }
        this.close();
    },
    
    close() {
        this.open = false;
        const el = document.querySelector('#confirmation-modal');
        if (typeof HSOverlay !== 'undefined') {
            HSOverlay.close(el);
        }
    }
}" @open-confirmation-modal.window="show($event.detail)" id="confirmation-modal"
    class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[100] overflow-x-hidden overflow-y-auto flex items-center justify-center">
    <div
        class="hs-overlay-open:opacity-100 hs-overlay-open:duration-500 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-white border shadow-sm rounded-xl">
            <div class="flex justify-between items-center py-3 px-4 border-b">
                <h3 class="font-bold text-gray-800" x-text="title"></h3>
                <button type="button" @click="close()"
                    class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none">
                    <span class="sr-only">Close</span>
                    <i class="uil uil-times text-lg"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p class="text-gray-800" x-text="message"></p>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t">
                <button type="button" @click="close()" class="btn btn-light" x-text="cancelText"></button>
                <button type="button" @click="confirm()" :class="'btn ' + confirmButtonClass"
                    x-text="confirmText"></button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmAction(event, message = null, title = null, callback = null) {
        if (event && event.preventDefault) {
            event.preventDefault();
        }
        const form = (event && event.target && event.target.tagName === 'FORM') ? event.target : null;

        window.dispatchEvent(new CustomEvent('open-confirmation-modal', {
            detail: {
                form: form,
                message: message,
                title: title,
                callback: callback
            }
        }));

        return false;
    }
</script>