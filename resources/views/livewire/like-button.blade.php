<div class="absolute bottom-2 left-2 z-50"> 
    <button
        wire:click="toggleLike"
        data-like-button
        class="px-2 py-1 rounded-full text-xs font-medium focus:outline-none transition-colors duration-200 shadow-md flex items-center space-x-1
        {{ $isLikedByCurrentUser
            ? 'bg-red-500 text-white hover:bg-red-600'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        }} z-50" 
    >
        <i class="{{ $isLikedByCurrentUser ? 'fas fa-heart' : 'far fa-heart' }}"></i>
        <span data-likes-count>{{ $likesCount }}</span>
    </button>
</div>