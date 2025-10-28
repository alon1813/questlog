<button wire:click="toggleVote" 
        @class([
            'flex items-center space-x-1 text-xs px-3 py-1 rounded-full border transition-colors',
            'bg-[var(--text-primary)] text-white border-[var(--text-primary)]' => $hasVoted,
            'border-[var(--border-color)] text-gray-400 hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)]' => !$hasVoted,
        ])>
    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M1 8.25a1.25 1.25 0 112.5 0v7.5a1.25 1.25 0 11-2.5 0v-7.5zM11 3V1.7c0-.268.14-.526.395-.707.255-.18.585-.218.868-.105l5.5 2.158a1.25 1.25 0 01.737 1.154v9.066a1.25 1.25 0 01-1.06 1.233l-5.786 1.815A1.25 1.25 0 0110 18.25v-5.757a.75.75 0 00-.22-.53L6.89 9.08a1.99 1.99 0 01-.68-.707l-.008-.016a5.002 5.002 0 01.44-5.207A4.981 4.981 0 0110.2 2.25c.19-.115.4-.19.6-.251.2-.06.4-.09.6-.099V3zm1.5 1.5a2.5 2.5 0 00-5 0v4.839l3.01 2.923a.75.75 0 001.056-.017l.934-.972V4.5z"></path></svg>
    <span>{{ $voteCount }} Útil</span>
</button>
