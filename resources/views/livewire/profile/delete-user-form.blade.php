<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-8">
            <div class="mb-8 border-b border-gray-50 pb-6">
                <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-widest mb-1">
                    {{ __('Konfirmasi Penghapusan') }}
                </h2>
                <p class="text-xs text-gray-500 mt-2">
                    {{ __('Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun ini secara permanen.') }}
                </p>
            </div>

            <div class="space-y-2 mb-8">
                <label for="del_password" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Kata Sandi Akun</label>
                <input
                    wire:model="password"
                    id="del_password"
                    type="password"
                    class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors"
                    placeholder="Masukkan sandi untuk konfirmasi"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-[10px] font-bold uppercase text-red-600" />
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
                <button type="button" x-on:click="$dispatch('close')" class="text-xs text-gray-500 hover:text-gray-800 font-medium transition-colors">
                    {{ __('Batalkan') }}
                </button>
                <button type="submit" class="bg-gray-900 text-white rounded-md px-6 py-2.5 text-sm font-medium hover:bg-black transition-all shadow-sm ml-auto">
                    {{ __('Hapus Akun Permanen') }}
                </button>
            </div>
        </form>
    </x-modal>
</div>
