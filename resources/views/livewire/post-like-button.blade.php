<div class="inline-flex items-center gap-2">
    <button
        wire:click="toggleLike"
        class="flex items-center space-x-2 px-4 py-2 rounded-full text-sm font-medium focus:outline-none transition-all duration-200 transform active:scale-95
        {{ $isLikedByCurrentUser
            ? 'bg-red-500 text-white hover:bg-red-600'
            : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'
        }}"
    >
        <i class="{{ $isLikedByCurrentUser ? 'fas fa-heart' : 'far fa-heart' }}"></i>
        <span>{{ $likesCount }}</span>
        <span class="hidden sm:inline">{{ $isLikedByCurrentUser ? 'Te gusta' : 'Me gusta' }}</span>
    </button>

    {{-- 🆕 Botón para ver quién dio like --}}
    @if($likesCount > 0)
        <button 
            @click="$dispatch('openLikesModal', { likeableId: {{ $post->id }}, likeableType: 'post' })"
            class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 underline">
            Ver quién
        </button>
    @endif
</div>