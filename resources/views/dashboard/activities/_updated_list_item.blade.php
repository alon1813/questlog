<p class="text-sm text-gray-800 dark:text-gray-200">
    <a href="{{ route('profiles.show', $activity->user) }}" class="font-bold hover:underline">{{ $activity->user->name }}</a>
    ha actualizado 
    <span class="font-bold">"{{ $activity->subject->title }}"</span> en su lista.
</p>
<p class="text-xs text-gray-500 dark:text-gray-400">
    {{ $activity->created_at->diffForHumans() }}
</p>            