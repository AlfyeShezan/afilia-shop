<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    use \App\Traits\HandlesCart;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->syncCart();

        session()->flash('message', 'Selamat datang di Afilia! Akun Anda telah berhasil dibuat.');

        $this->redirect(route('home', absolute: false), navigate: false);
    }
}; ?>

<div class="min-h-[80vh] flex flex-col justify-center py-12 px-4">
    <div class="max-w-md w-full mx-auto space-y-8">

        {{-- Brand --}}
        <div class="text-center">
            <a href="{{ route('home') }}" wire:navigate>
                <span class="text-xl font-bold tracking-tight text-gray-900">AFILIA <span class="text-pink-500">MARKET</span></span>
            </a>
            <p class="mt-2 text-sm text-gray-500">Buat akun baru</p>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-gray-200 rounded-md p-8 space-y-6">
            <form wire:submit="register" class="space-y-5">
                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                    <input wire:model="name" id="name" type="text" required autofocus
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white"
                        placeholder="Nama Anda">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm text-gray-600 mb-1">Email</label>
                    <input wire:model="email" id="email" type="email" required
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white"
                        placeholder="email@anda.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm text-gray-600 mb-1">Kata Sandi</label>
                    <input wire:model="password" id="password" type="password" required
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm text-gray-600 mb-1">Konfirmasi Kata Sandi</label>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password" required
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-gray-900 text-white text-sm font-medium py-2.5 rounded-md hover:bg-black transition-colors">
                    Buat Akun
                </button>
            </form>

            <div class="pt-4 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" wire:navigate class="text-gray-800 font-medium hover:underline ml-1">Masuk di sini</a>
                </p>
            </div>
        </div>

    </div>
</div>


