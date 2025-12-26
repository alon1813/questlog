<button
    wire:click="toggleLike"
    class="flex items-center space-x-1 text-xs px-2 py-1 rounded-full transition-colors
    {{ $isLikedByCurrentUser
        ? 'bg-red-500 text-white hover:bg-red-600'
        : 'text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400'
    }}"
>
    <i class="{{ $isLikedByCurrentUser ? 'fas fa-heart' : 'far fa-heart' }} text-xs"></i>
    <span>{{ $likesCount }}</span>
</button>