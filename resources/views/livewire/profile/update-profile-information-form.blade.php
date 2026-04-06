<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<div class="space-y-5">
    <form wire:submit="updateProfileInformation" class="space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Name Field --}}
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-widest">Nama Lengkap</label>
                <input wire:model="name" id="name" type="text" 
                    class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors"
                    required autofocus autocomplete="name">
                @error('name') <p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p> @enderror
            </div>

            {{-- Email Field --}}
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-medium text-gray-500 uppercase tracking-widest">Alamat Email</label>
                <input wire:model="email" id="email" type="email" 
                    class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors"
                    required autocomplete="username">
                @error('email') <p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p> @enderror
            </div>
            
            {{-- Phone Field --}}
            <div class="space-y-1.5 md:col-span-2">
                <label for="phone" class="block text-xs font-medium text-gray-500 uppercase tracking-widest">Nomor Telepon</label>
                <input wire:model="phone" id="phone" type="text" 
                    class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors"
                    placeholder="08123456789">
                @error('phone') <p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p> @enderror
            </div>
        </div>

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div class="p-4 bg-gray-50 border border-gray-100 rounded-md">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                    {{ __('Email Anda belum diverifikasi.') }}
                    <button wire:click.prevent="sendVerification" class="ml-2 text-gray-900 underline hover:text-black transition-colors">
                        {{ __('Verifikasi Sekarang') }}
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-[9px] font-bold text-green-600 uppercase tracking-widest italic">
                        {{ __('Link verifikasi baru telah dikirim ke email Anda.') }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-50">
            <span x-data="{ show: false }" 
                  x-on:profile-updated.window="show = true; setTimeout(() => show = false, 3000)" 
                  x-show="show" 
                  x-transition
                  class="text-[10px] font-bold text-green-600 uppercase tracking-widest">
                {{ __('Profil Berhasil Diperbarui') }}
            </span>

            <button type="submit" class="bg-gray-900 text-white rounded-md px-6 py-2.5 text-sm font-medium hover:bg-gray-800 transition-all shadow-sm">
                <span wire:loading.remove wire:target="updateProfileInformation">Simpan Perubahan</span>
                <span wire:loading wire:target="updateProfileInformation">Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
