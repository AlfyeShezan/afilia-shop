<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<div class="space-y-6">
    <form wire:submit="updatePassword" class="space-y-6">
        {{-- Current Password --}}
        <div class="space-y-1.5">
            <label for="current_password" class="block text-xs font-medium text-gray-500 uppercase tracking-widest">Kata Sandi Saat Ini</label>
            <input wire:model="current_password" id="current_password" type="password" 
                class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors"
                autocomplete="current-password">
            @error('current_password') <p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- New Password --}}
            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-medium text-gray-500 uppercase tracking-widest">Kata Sandi Baru</label>
                <input wire:model="password" id="password" type="password" 
                    class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors"
                    autocomplete="new-password">
                @error('password') <p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p> @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-xs font-medium text-gray-500 uppercase tracking-widest">Konfirmasi Sandi</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" 
                    class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors"
                    autocomplete="new-password">
                @error('password_confirmation') <p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="p-4 bg-gray-50 border border-gray-100 rounded-md">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-relaxed">
                {{ __('Tip: Gunakan kombinasi huruf besar, kecil, angka, dan simbol untuk keamanan maksimal.') }}
            </p>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-50">
            <span x-data="{ show: false }" 
                  x-on:password-updated.window="show = true; setTimeout(() => show = false, 3000)" 
                  x-show="show" 
                  x-transition
                  class="text-[10px] font-bold text-green-600 uppercase tracking-widest">
                {{ __('Sandi Berhasil Diperbarui') }}
            </span>

            <button type="submit" class="bg-gray-900 text-white rounded-md px-6 py-2.5 text-sm font-medium hover:bg-gray-800 transition-all shadow-sm">
                <span wire:loading.remove wire:target="updatePassword">Perbarui Kata Sandi</span>
                <span wire:loading wire:target="updatePassword">Memproses...</span>
            </button>
        </div>
    </form>
</div>
