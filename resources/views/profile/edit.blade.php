<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            @if (session('success'))
                <div class="p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mt-6">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Mi Lista de Juegos
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Aquí están todos los juegos que has añadido a tu colección.
                    </p>
                </div>

                <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">

                    @if($user->items->isNotEmpty())
                        @foreach ($user->items as $item)
                            <a href="{{ route('user-list.edit', $item->pivot->id) }}" class="block group relative">
                                @php
                                    $statusColor = match ($item->pivot->status) {
                                        'Jugando' => 'bg-blue-500',
                                        'Completado' => 'bg-green-500',
                                        'Abandonado' => 'bg-red-500',
                                        default => 'bg-gray-500',
                                    };
                                @endphp
                                <div class="absolute top-2 right-2 z-10 px-2 py-1 text-xs font-semibold text-white rounded {{ $statusColor }}">
                                    @if ($item->pivot->status === 'Jugando')
                                        {{ $item->type === 'anime' ? 'Viendo' : 'Jugando' }}
                                    @else
                                        {{ $item->pivot->status }}
                                    @endif
                                </div>

                                <img src="{{ $item->cover_image_url }}" alt="Portada de {{ $item->title }}" class="rounded-lg w-full h-full object-cover aspect-[3/4] group-hover:opacity-75 transition-opacity">

                                <div class="absolute inset-0 bg-black bg-opacity-70 rounded-lg flex flex-col justify-end p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <h4 class="text-white font-bold text-sm">{{ $item->title }}</h4>

                                    @if ($item->pivot->score)
                                        <p class="text-yellow-400 font-bold mt-1">⭐ {{ $item->pivot->score }} / 10</p>
                                    @endif
                                    
                                    @if ($item->type === 'anime' && $item->episodes > 0)
                                        <div class="mt-1 text-xs text-gray-300">
                                            <p>Vistos: {{ $item->pivot->episodes_watched }} / {{ $item->episodes }}</p>
                                            {{-- Opcional: una barra de progreso visual --}}
                                            <div class="w-full bg-gray-600 rounded-full h-1.5 mt-1">
                                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: '{{ ($item->pivot->episodes_watched / $item->episodes) * 100 }}%'"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @else
                        <p class="text-gray-600 dark:text-gray-400 col-span-full">
                            Aún no has añadido ningún juego a tu lista. ¡Ve al <a href="{{ route('search.index') }}" class="underline">buscador</a> para empezar!
                        </p>
                    @endif

                </div>

            </div> </div>
    </div>


</x-app-layout>