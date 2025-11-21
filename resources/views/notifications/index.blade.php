<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-6 text-white">Notificaciones</h1>
            <div class="space-y-3">
                @forelse ($notifications as $notification)
                    @php
                        
                        $link = '#';
                        $icon = '🔔';
                        $bgClass = $notification->read_at 
                            ? 'bg-[var(--bg-secondary)]' 
                            : 'bg-[var(--bg-tertiary)] border-l-4 border-[var(--text-primary)]';
                        switch ($notification->type) {
                            case 'App\Notifications\NewFollowerNotification':
                                $icon = '👥';
                                
                                if (isset($notification->data['follower_username']) && !empty($notification->data['follower_username'])) {
                                    try {
                                        $link = route('profiles.show', ['user' => $notification->data['follower_username']]);
                                    } catch (\Exception $e) {
                                        $link = '/notificaciones';
                                    }
                                }
                                break;
                            
                            case 'App\Notifications\NewCommentNotification':
                                $icon = '💬';
                                
                                if (isset($notification->data['post_id']) && !empty($notification->data['post_id'])) {
                                    try {
                                        $link = route('posts.show', $notification->data['post_id']);
                                    } catch (\Exception $e) {
                                        $link = '/notificaciones';
                                    }
                                }
                                break;
                            
                            case 'App\Notifications\NewLikeNotification':
                                $icon = '❤️';
                                
                                if (isset($notification->data['liker_username']) && !empty($notification->data['liker_username'])) {
                                    try {
                                        $link = route('profiles.show', ['user' => $notification->data['liker_username']]);
                                    } catch (\Exception $e) {
                                        $link = '/notificaciones';
                                    }
                                }
                                break;
                            
                            default:
                                $link = '/notificaciones';
                                break;
                        }
                    @endphp
                    
                    <a 
                        href="{{ $link }}" 
                        class="block {{ $bgClass }} p-4 rounded-lg hover:bg-[var(--bg-tertiary)] transition-colors"
                    >
                        <div class="flex items-center gap-4">
                            <span class="text-2xl">{{ $icon }}</span>
                            <div class="flex-grow">
                                <p class="text-white">
                                    {{ $notification->data['message'] ?? 'Nueva notificación' }}
                                </p>
                                <span class="text-xs text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            @if (!$notification->read_at)
                                <span class="flex-shrink-0 inline-block w-3 h-3 bg-blue-500 rounded-full"></span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="bg-[var(--bg-secondary)] p-8 rounded-lg text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-gray-400 text-lg">No tienes notificaciones.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>