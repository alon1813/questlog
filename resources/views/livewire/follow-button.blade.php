<div>
    @if ($isFollowing)
        <button wire:click="toggleFollow" class="px-6 py-2 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-500">
            Dejar de Seguir
        </button>
    @else
        <button wire:click="toggleFollow" class="px-6 py-2 bg-[var(--text-primary)] text-white font-bold rounded-lg hover:opacity-80">
            Seguir
        </button>
    @endif
</div>