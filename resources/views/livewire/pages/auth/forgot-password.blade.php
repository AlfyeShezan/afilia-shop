<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="min-h-[80vh] flex flex-col justify-center py-12 px-4">
    <div class="max-w-md w-full mx-auto space-y-8">

        {{-- Brand --}}
        <div class="text-center">
            <a href="{{ route('home') }}" wire:navigate>
                <span class="text-xl font-bold tracking-tight text-gray-900">AFILIA <span class="text-pink-500">MARKET</span></span>
            </a>
            <h1 class="mt-4 text-base font-medium text-gray-800">Lupa Kata Sandi</h1>
            <p class="mt-1 text-sm text-gray-500 max-w-xs mx-auto">
                Masukkan email Anda untuk menerima tautan pengaturan ulang kata sandi.
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-gray-200 rounded-md p-8 space-y-6">
            <x-auth-session-status class="mb-2" :status="session('status')" />

            <form wire:submit="sendPasswordResetLink" class="space-y-5">
                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm text-gray-600 mb-1">Alamat Email</label>
                    <input wire:model="email" id="email" type="email" required autofocus
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white"
                        placeholder="email@anda.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                {{-- Submit --}}
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-gray-900 text-white text-sm font-medium py-2.5 rounded-md hover:bg-black transition-colors disabled:opacity-60">
                    <span wire:loading.remove>Kirim Tautan</span>
                    <span wire:loading>Mengirim...</span>
                </button>
            </form>

            <div class="pt-4 border-t border-gray-100 text-center">
                <a href="{{ route('login') }}" wire:navigate
                    class="text-sm text-gray-500 hover:text-gray-800 transition-colors">
                    ← Kembali ke Masuk
                </a>
            </div>
        </div>

    </div>
</div>
