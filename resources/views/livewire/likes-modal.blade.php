<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
                 @click="$wire.closeModal()"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
            </div>

            {{-- Modal --}}
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full max-h-[80vh] overflow-hidden"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-4"
                     @click.away="$wire.closeModal()">
                    
                    {{-- Header --}}
                    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-heart text-red-500"></i>
                            Likes ({{ count($likes) }})
                        </h3>
                        <button wire:click="closeModal" 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Lista de usuarios --}}
                    <div class="overflow-y-auto max-h-96">
                        @forelse($likes as $like)
                            <a href="{{ route('profiles.show', $like->user->username) }}" 
                               class="flex items-center gap-3 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <img src="{{ $like->user->avatar_path ? asset('storage/' . $like->user->avatar_path) : asset('images/default-avatar.png') }}" 
                                     alt="{{ $like->user->name }}"
                                     class="w-12 h-12 rounded-full object-cover">
                                
                                <div class="flex-grow">
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $like->user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        @{{ $like->user->username }}
                                    </div>
                                </div>

                                <i class="fas fa-heart text-red-500"></i>
                            </a>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                Nadie ha dado like todavía
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>