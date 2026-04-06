<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-white text-[8px] font-black text-white flex items-center justify-center">{{ $unreadCount }}</span>
            </span>
        @endif
    </button>

    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="fixed sm:absolute top-16 sm:top-auto left-4 right-4 sm:left-auto sm:right-0 sm:mt-2 sm:w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50">
        
        <div class="p-4 border-b border-gray-50 flex items-center justify-between bg-white sticky top-0 z-10">
            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Notifikasi</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-[9px] font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-tight">Tandai semua dibaca</button>
            @endif
        </div>

        <div class="max-h-[70vh] sm:max-h-96 overflow-y-auto no-scrollbar">
            @forelse($notifications as $notification)
                <div class="p-4 border-b border-gray-50 transition-colors {{ $notification->is_read ? 'bg-white' : 'bg-indigo-50/30' }}">
                    <div class="flex gap-3">
                        <div class="shrink-0">
                            @if($notification->type === 'order')
                                <div class="w-10 h-10 sm:w-8 sm:h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                            @elseif($notification->type === 'promo')
                                <div class="w-10 h-10 sm:w-8 sm:h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4v-3a2 2 0 00-2-2H5z"/></svg>
                                </div>
                            @else
                                <div class="w-10 h-10 sm:w-8 sm:h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-900 leading-tight">{{ $notification->title }}</p>
                            <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">{{ $notification->message }}</p>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-[9px] text-gray-400 font-medium">{{ $notification->created_at->diffForHumans() }}</span>
                                @if(!$notification->is_read)
                                    <button wire:click="markAsRead({{ $notification->id }})" class="text-[10px] sm:text-[9px] font-bold text-indigo-600 hover:underline tracking-tight">Tandai dibaca</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Belum ada notifikasi</p>
                </div>
            @endforelse
        </div>

        <div class="p-3 bg-gray-50 text-center border-t border-gray-100 sticky bottom-0 z-10">
            <a href="{{ route('notifications') }}" wire:navigate class="block w-full text-[9px] font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition-colors">Lihat Semua Notifikasi</a>
        </div>
    </div>
</div>
