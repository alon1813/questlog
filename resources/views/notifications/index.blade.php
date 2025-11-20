<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-6">Notificaciones</h1>
            <div class="space-y-3">
                @forelse ($notifications as $notification)
                
                    <div @class([
                        'flex items-center gap-4 p-4 rounded-lg',
                        'bg-[var(--bg-tertiary)] border-l-4 border-[var(--text-primary)]' => !$notification->read_at,
                        'bg-[var(--bg-secondary)]' => $notification->read_at,
                    ])>
                        {{-- Aquí podrías añadir la lógica para mostrar el avatar del usuario que genera la notificación --}}
                        <div class="flex-grow">
                            @if ($notification->type === 'App\Notifications\NewFollowerNotification')
                                <p>
                                    <a href="#" class="font-bold hover:underline">{{ $notification->data['follower_name'] }}</a>
                                    {{ $notification->data['message'] }}
                                </p>
                            @elseif ($notification->type === 'App\Notifications\NewCommentNotification')
                                <p>
                                    <a href="#" class="font-bold hover:underline">{{ $notification->data['commenter_name'] }}</a>
                                    {{ $notification->data['message'] }}
                                    <a href="{{ route('posts.show', $notification->data['post_id']) }}" class="font-bold hover:underline">"{{ Str::limit($notification->data['post_title'], 30) }}"</a>
                                </p>
                            @endif
                            <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="bg-[var(--bg-secondary)] p-8 rounded-lg text-center">
                        <p class="text-gray-400">No tienes notificaciones.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>