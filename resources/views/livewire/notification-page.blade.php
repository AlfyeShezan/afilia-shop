<div class="py-8 sm:py-12 bg-white min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Notifikasi</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Simak kabar terbaru dari Afilia</p>
            </div>
            @if($notifications->count() > 0)
                <button wire:click="markAllAsRead" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-tight border border-indigo-100 px-4 py-2 rounded-md hover:bg-indigo-50 transition-all">Tandai Semua Dibaca</button>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="p-6 border rounded-xl transition-all {{ $notification->is_read ? 'border-gray-100 bg-white' : 'border-indigo-100 bg-indigo-50/20' }}">
                    <div class="flex flex-col sm:flex-row gap-5">
                        <div class="shrink-0">
                            @if($notification->type === 'order')
                                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                            @elseif($notification->type === 'promo')
                                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4v-3a2 2 0 00-2-2H5z"/></svg>
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                <h3 class="text-sm font-bold text-gray-900 leading-tight">{{ $notification->title }}</h3>
                                <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">{{ $notification->message }}</p>
                            
                            <div class="flex items-center gap-4">
                                @if($notification->link)
                                    <a href="{{ $notification->link }}" class="text-[10px] font-bold text-gray-900 uppercase tracking-widest hover:underline">Lihat Detail</a>
                                @endif
                                
                                @if(!$notification->is_read)
                                    <button wire:click="markAsRead({{ $notification->id }})" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-tight">Tandai Dibaca</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center border border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                    <div class="w-20 h-20 bg-white shadow-sm rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    </div>
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-[0.2em]">Belum ada notifikasi</h3>
                    <p class="text-xs text-gray-400 mt-2">Kabar terbaru pesanan atau promo Anda akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
