<p class="text-sm text-gray-800 dark:text-gray-200">
    <a href="{{ route('profiles.show', $activity->user) }}" class="font-bold hover:underline">{{ $activity->user->name }}</a>
    ha actualizado la noticia:
    <a href="{{ route('posts.show', $activity->subject) }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">"{{ $activity->subject->title }}"</a>
</p>
<p class="text-xs text-gray-500 dark:text-gray-400">
    {{ $activity->created_at->diffForHumans() }}
</p>