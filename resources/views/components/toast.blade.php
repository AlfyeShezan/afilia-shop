{{--
    Toast Notification Component
    Events: window.dispatchEvent(new CustomEvent('notify', { detail: { message: '...', type: 'success|error|warning|info' } }))
    Livewire: $this->dispatch('notify', ['message' => '...', 'type' => 'success'])
--}}
<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            @if(session()->has('message'))
                this.showToast('{{ session('message') }}', 'success');
            @endif
            @if(session()->has('error'))
                this.showToast('{{ session('error') }}', 'error');
            @endif

            window.addEventListener('notify', (event) => {
                const detail = event.detail[0] ?? event.detail;
                this.showToast(detail.message, detail.type || 'success');
            });
        },
        showToast(message, type) {
            this.message = message;
            this.type = type;
            this.show = true;
            setTimeout(() => this.show = false, 5000);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-1"
    class="fixed bottom-6 right-6 z-[100] w-[340px]"
    style="display: none;"
>
    <div
        :class="{
            'border-l-green-500 bg-green-50 border-green-200':  type === 'success',
            'border-l-red-500 bg-red-50 border-red-200':        type === 'error',
            'border-l-yellow-500 bg-yellow-50 border-yellow-200': type === 'warning',
            'border-l-blue-500 bg-blue-50 border-blue-200':     type === 'info'
        }"
        class="flex items-start gap-3 px-4 py-3 rounded-md border border-l-4"
    >
        {{-- Icon --}}
        <div class="shrink-0 mt-0.5">
            {{-- Success --}}
            <template x-if="type === 'success'">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </template>
            {{-- Error --}}
            <template x-if="type === 'error'">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </template>
            {{-- Warning --}}
            <template x-if="type === 'warning'">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </template>
            {{-- Info --}}
            <template x-if="type === 'info'">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <p class="text-[9px] font-bold uppercase tracking-widest mb-0.5"
                :class="{
                    'text-green-600':  type === 'success',
                    'text-red-600':    type === 'error',
                    'text-yellow-600': type === 'warning',
                    'text-blue-600':   type === 'info'
                }"
                x-text="{ success: 'SUKSES', error: 'GAGAL', warning: 'PERHATIAN', info: 'INFO' }[type] ?? type.toUpperCase()"
            ></p>
            <p class="text-sm text-gray-700 leading-snug" x-text="message"></p>
        </div>

        {{-- Close --}}
        <button @click="show = false" class="shrink-0 text-gray-400 hover:text-gray-600 transition-colors mt-0.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
