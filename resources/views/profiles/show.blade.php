<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col sm:flex-row items-center gap-8 mb-8">
                <img class="h-40 w-40 rounded-full object-cover border-4 border-[var(--text-primary)]" src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('images/default-avatar.png') }}" alt="Avatar de {{ $user->name }}">
                <div class="text-center sm:text-left">
                    <h2 class="text-4xl font-black text-white">{{ $user->name }}</h2>
                    <p class="text-xl text-gray-400 mb-2">@ {{ $user->username }}</p>

                    <div class="flex justify-center sm:justify-start space-x-6 text-white mb-4">
                        <div><span class="font-bold">{{ $user->followers_count }}</span> <span class="text-gray-400">Seguidores</span></div>
                        <div><span class="font-bold">{{ $user->following_count }}</span> <span class="text-gray-400">Siguiendo</span></div>
                    </div>

                    {{-- @auth
                        @if (auth()->user()->id !== $user->id)
                            @if (auth()->user()->isFollowing($user))
                                <form action="{{ route('users.unfollow', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-6 py-2 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-500">Dejar de Seguir</button>
                                </form>
                            @else
                                <form action="{{ route('users.follow', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-2 bg-[var(--text-primary)] text-white font-bold rounded-lg hover:opacity-80">Seguir</button>
                                </form>
                            @endif
                        @endif
                    @endauth --}}
                    @auth
                        @if (auth()->user()->id !== $user->id)
                            <livewire:follow-button :user="$user" />
                        @endif
                    @endauth
                </div>
            </div>

        </div>
    </div>
</x-app-layout>