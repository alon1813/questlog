<p class="text-sm text-gray-800 dark:text-gray-200">
    <span class="inline-flex items-center gap-2">
        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
        </svg>
        <span class="font-bold text-[var(--text-primary)]">{{ $activity->user->name }}</span>
    </span>
    se ha unido a QuestLog. ¡Bienvenido a la comunidad! 🎮
</p>
<p class="text-xs text-gray-500 dark:text-gray-400">
    {{ $activity->created_at->diffForHumans() }}
</p>