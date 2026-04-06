<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email');
    }

    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="min-h-[80vh] flex flex-col justify-center py-12 px-4">
    <div class="max-w-md w-full mx-auto space-y-8">

        {{-- Brand --}}
        <div class="text-center">
            <a href="{{ route('home') }}" wire:navigate>
                <span class="text-xl font-bold tracking-tight text-gray-900">AFILIA <span class="text-pink-500">MARKET</span></span>
            </a>
            <h1 class="mt-4 text-base font-medium text-gray-800">Atur Ulang Kata Sandi</h1>
            <p class="mt-1 text-sm text-gray-500">Buat kata sandi baru untuk akun Anda.</p>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-gray-200 rounded-md p-8 space-y-6">
            <form wire:submit="resetPassword" class="space-y-5">

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm text-gray-600 mb-1">Email</label>
                    <input wire:model="email" id="email" type="email" required autofocus autocomplete="username"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                {{-- Kata Sandi Baru --}}
                <div>
                    <label for="password" class="block text-sm text-gray-600 mb-1">Kata Sandi Baru</label>
                    <input wire:model="password" id="password" type="password" required autocomplete="new-password"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                {{-- Konfirmasi Kata Sandi --}}
                <div>
                    <label for="password_confirmation" class="block text-sm text-gray-600 mb-1">Konfirmasi Kata Sandi</label>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                {{-- Submit --}}
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-gray-900 text-white text-sm font-medium py-2.5 rounded-md hover:bg-black transition-colors disabled:opacity-60">
                    <span wire:loading.remove>Atur Ulang Kata Sandi</span>
                    <span wire:loading>Memproses...</span>
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
