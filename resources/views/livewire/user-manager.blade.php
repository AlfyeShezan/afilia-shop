<div class="max-w-7xl mx-auto px-6 py-8 space-y-8 min-h-screen bg-gray-50">
    {{-- 1️⃣ HEADER --}}
    <div class="space-y-1">
        <p class="text-xs text-gray-400 uppercase tracking-widest">Admin / Manajemen</p>
        <h1 class="text-xl font-semibold text-gray-800">Manajemen Pengguna</h1>
        <p class="text-sm text-gray-600">Kelola otoritas, profil, dan status keaktifan pengguna.</p>
    </div>

    {{-- NOTIFICATION --}}
    @if (session()->has('message'))
        <div class="bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest p-4 rounded-md flex justify-between items-center animate-fade-in">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white">&times;</button>
        </div>
    @endif

    {{-- 2️⃣ STATISTIK RINGKAS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <p class="text-xs uppercase text-gray-500 tracking-wider mb-1">Total Pengguna</p>
            <p class="text-lg font-semibold text-gray-800">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <p class="text-xs uppercase text-gray-500 tracking-wider mb-1">Status Aktif</p>
            <p class="text-lg font-semibold text-gray-800">{{ number_format($stats['active']) }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <p class="text-xs uppercase text-gray-500 tracking-wider mb-1">Admin / Staff</p>
            <p class="text-lg font-semibold text-gray-800">{{ number_format($stats['admins']) }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <p class="text-xs uppercase text-gray-500 tracking-wider mb-1">Vendor</p>
            <p class="text-lg font-semibold text-gray-800">{{ number_format($stats['vendors']) }}</p>
        </div>
    </div>

    {{-- 3️⃣ TOOLBAR --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..." 
                    class="w-full pl-10 border border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors">
            </div>
            <select wire:model.live="roleFilter" 
                class="w-full md:w-auto border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none bg-white">
                <option value="">Semua Peran</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ ucwords(str_replace('-', ' ', $role->name)) }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="openCreateModal" 
            class="bg-gray-900 text-white rounded-md px-4 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Pengguna
        </button>
    </div>

    {{-- 4️⃣ TABEL PENGGUNA --}}
    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Pengguna</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Peran</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Bergabung</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr wire:key="user-{{ $user->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-medium text-gray-600 border border-gray-200 uppercase">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-800">{{ $user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2.5 py-1 rounded-full border border-gray-200 text-gray-600 bg-gray-50/50">
                                {{ ucwords(str_replace('-', ' ', $user->getRoleNames()->first() ?? 'Customer')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleStatus({{ $user->id }})" 
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors {{ $user->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                {{ $user->is_active ? 'Active' : 'Locked' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-[13px]">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <button wire:click="editUser({{ $user->id }})" class="text-xs text-gray-600 hover:text-black font-medium transition-colors">Edit</button>
                                <button wire:click="confirmDelete({{ $user->id }})" class="text-xs text-red-600/70 hover:text-red-600 font-medium transition-colors">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">Tidak ada pengguna ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    {{-- MODAL AREA --}}
    @if($isEditMode)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-[2px] transition-opacity" wire:click="cancelEdit"></div>
        <div class="bg-white rounded-md border border-gray-200 relative w-full max-w-xl overflow-hidden animate-slide-up">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center text-gray-800">
                <h3 class="text-base font-semibold">{{ $userId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h3>
                <button wire:click="cancelEdit" class="text-gray-400 hover:text-gray-800 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors">
                        @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Email</label>
                        <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors">
                        @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Telepon</label>
                        <input type="text" wire:model="phone" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors">
                        @error('phone') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    @if(!$userId)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Password</label>
                        <input type="password" wire:model="password" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors">
                        @error('password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Konfirmasi</label>
                        <input type="password" wire:model="password_confirmation" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors">
                    </div>
                    @endif

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Otoritas Peran</label>
                        <select wire:model="selectedRole" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors bg-white">
                            <option value="">Pilih Peran</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucwords(str_replace('-', ' ', $role->name)) }}</option>
                            @endforeach
                        </select>
                        @error('selectedRole') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 flex items-center gap-2 p-3 bg-gray-50 rounded-md border border-gray-100">
                        <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        <label for="is_active" class="text-sm text-gray-700">Akun Aktif (Dapat Login)</label>
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                    <button type="submit" class="bg-gray-900 text-white rounded-md px-6 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">
                        {{ $userId ? 'Simpan Perubahan' : 'Daftarkan Pengguna' }}
                    </button>
                    <button type="button" wire:click="cancelEdit" class="bg-white border border-gray-300 text-gray-600 rounded-md px-6 py-2.5 text-sm font-medium hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- DELETE CONFIRMATION --}}
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-[2px] transition-opacity" wire:click="$set('confirmingDeletion', null)"></div>
        <div class="bg-white rounded-md border border-gray-200 relative w-full max-w-sm overflow-hidden animate-slide-up">
            <div class="p-8 text-center space-y-4">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-50 text-red-600 border border-red-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-semibold text-gray-800">Hapus Pengguna?</h3>
                    <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan secara manual.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-4">
                    <button wire:click="deleteUser" class="bg-red-600 text-white rounded-md py-2.5 text-sm font-medium hover:bg-red-700 transition-colors">Ya, Hapus</button>
                    <button wire:click="$set('confirmingDeletion', null)" class="bg-white border border-gray-300 text-gray-600 rounded-md py-2.5 text-sm font-medium hover:bg-gray-50 transition-colors">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slide-up { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
        .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }
    </style>
</div>
