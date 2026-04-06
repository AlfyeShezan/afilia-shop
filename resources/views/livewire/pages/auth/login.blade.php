<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    use \App\Traits\HandlesCart;

    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        try {
            $this->validate();

            $this->form->authenticate();

            Session::regenerate();

            $this->syncCart();

            session()->flash('message', 'Selamat datang kembali! Anda telah berhasil masuk.');

            $this->redirect('/', navigate: false);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }
            
            $this->dispatch('notify', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }
}; ?>

<div class="min-h-[80vh] flex flex-col justify-center py-12 px-4">
    <div class="max-w-md w-full mx-auto space-y-8">

        {{-- Brand --}}
        <div class="text-center">
            <a href="{{ route('home') }}" wire:navigate>
                <span class="text-xl font-bold tracking-tight text-gray-900">AFILIA <span class="text-pink-500">MARKET</span></span>
            </a>
            <p class="mt-2 text-sm text-gray-500">Masuk ke akun Anda</p>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-gray-200 rounded-md p-8 space-y-6">
            <x-auth-session-status class="mb-2" :status="session('status')" />

            <form wire:submit="login" class="space-y-5">
                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm text-gray-600 mb-1">Email</label>
                    <input wire:model="form.email" id="email" type="email" required autofocus
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white"
                        placeholder="email@anda.com">
                    <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="text-sm text-gray-600">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate
                                class="text-sm text-gray-500 hover:text-gray-800 transition-colors">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                    <input wire:model="form.password" id="password" type="password" required
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-400">
                    <label for="remember" class="text-sm text-gray-600 cursor-pointer">Ingat saya selama 30 hari</label>
                </div>

                {{-- Submit --}}
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-gray-900 text-white text-sm font-medium py-2.5 rounded-md hover:bg-black transition-colors disabled:opacity-60">
                    <span wire:loading.remove>Masuk</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </form>

            <div class="pt-4 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">
                    Baru di Afilia?
                    <a href="{{ route('register') }}" wire:navigate class="text-gray-800 font-medium hover:underline ml-1">Buat akun</a>
                </p>
            </div>
        </div>

    </div>
</div>


