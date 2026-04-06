<div class="p-6 bg-white min-h-screen">
    <div class="mb-8 flex justify-between items-center border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">Manajemen Vendor</h1>
            <p class="text-sm text-gray-500 mt-1">Tinjau dan kelola penjual platform</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <!-- Controls -->
    <div class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-1/2">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berdasarkan nama toko atau pemilik..." class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <select wire:model.live="statusFilter" class="border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Semua Status</option>
                <option value="pending">Tertunda</option>
                <option value="active">Aktif</option>
                <option value="suspended">Ditangguhkan</option>
            </select>
        </div>
    </div>

    <!-- Vendors Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Toko / Pemilik</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bergabung</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($vendors as $vendor)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900">{{ $vendor->name }}</div>
                                <div class="text-xs text-gray-500 italic">{{ $vendor->user->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest text-white
                            @if($vendor->status == 'active') bg-green-600
                            @elseif($vendor->status == 'pending') bg-yellow-400 text-yellow-900
                            @else bg-red-600 @endif">
                            {{ $vendor->status == 'active' ? 'Aktif' : ($vendor->status == 'pending' ? 'Tertunda' : 'Ditangguhkan') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 italic">
                        {{ $vendor->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button wire:click="editVendor({{ $vendor->id }})" class="px-3 py-1.5 bg-blue-600 text-white hover:bg-blue-700 font-bold uppercase text-[10px] rounded transition-colors">Edit</button>
                            <button wire:click="confirmDelete({{ $vendor->id }})" class="px-3 py-1.5 bg-red-600 text-white hover:bg-red-700 font-bold uppercase text-[10px] rounded transition-colors">Hapus</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">
                        Tidak ada vendor ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $vendors->links() }}
        </div>
    </div>

    <!-- Edit Vendor Modal -->
    @if($isEditMode)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cancelEdit"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="text-lg font-black uppercase tracking-widest">Kelola Vendor</h3>
                    <button wire:click="cancelEdit" class="text-white hover:text-indigo-200 transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form wire:submit.prevent="save" class="p-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Toko</label>
                            <input type="text" wire:model="name" class="w-full border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold">
                            @error('name') <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Deskripsi Toko</label>
                            <textarea wire:model="description" rows="3" class="w-full border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm italic"></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-indigo-600 uppercase tracking-tighter mb-1">Status Akun</label>
                            <select wire:model="status" class="w-full text-xs font-bold border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="pending">Menunggu Persetujuan</option>
                                <option value="active">Disetujui / Aktif</option>
                                <option value="suspended">Ditangguhkan</option>
                            </select>
                            @error('status') <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                        <button type="button" wire:click="cancelEdit" class="px-5 py-2 border border-gray-300 rounded text-xs font-bold text-gray-600 uppercase hover:bg-gray-50 transition-all">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 rounded text-xs font-bold text-white uppercase shadow-md hover:bg-indigo-700 transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('confirmingDeletion', null)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 uppercase tracking-widest" id="modal-title">Hapus Vendor</h3>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500 italic">Apakah Anda yakin ingin menghapus toko vendor ini? Ini tidak akan menghapus akun pengguna, tetapi akan menonaktifkan kemampuan berjualan mereka.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteVendor" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-xs font-black text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto">Konfirmasi Penghapusan</button>
                    <button wire:click="$set('confirmingDeletion', null)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-xs font-black text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
