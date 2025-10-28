<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing - QuestLog</title>
    <link rel="icon" type="image/png" href="{{ asset('favicons/source/favicon-96x96.png') }}" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicons/source/favicon.svg') }}" />
        <link rel="shortcut icon" href="{{ asset('favicons/source/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/source/apple-touch-icon.png') }}" />
        <link rel="manifest" href="{{ asset('favicons/source/site.webmanifest') }}" />
    {{-- Vite se encarga de importar app.css que a su vez importa las fuentes y tus estilos personalizados --}}
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
    
    @stack('styles') 
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    
</head>
<body class="bg-[var(--bg-primary)] text-[var(--text-secondary)] font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="flex justify-between items-center py-5 border-b border-[var(--border-color)]">
            <div class="text-2xl font-extrabold text-white">QuestLog</div>
            <nav class="hidden md:flex space-x-6">
                <a href="#features-section" class="font-medium text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors">Características</a>
                <a href="{{ route('posts.index') }}" class="font-medium text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors">Blog</a>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="cta-button cta-button-secondary">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="cta-button">Regístrate Gratis</a>
            </div>
        </header>

        <main>
            <section class="text-center py-20 md:py-32 relative overflow-hidden rounded-xl mt-8" 
                    style="background-image: linear-gradient(rgba(26, 26, 46, 0.8), rgba(26, 26, 46, 0.95)), url('https://images.alphacoders.com/133/1330425.png'); background-size: cover; background-position: center;">
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-5 leading-tight">Tu universo de juegos y anime, en un solo lugar.</h1>
                <p class="text-lg md:text-xl text-[var(--text-secondary)] max-w-2xl mx-auto mb-10">Cataloga, puntúa y comparte todo lo que juegas y ves. Conecta con una comunidad que comparte tu misma pasión.</p>
                <a href="{{ route('register') }}" class="cta-button text-lg px-8 py-4">Regístrate Gratis</a>
            </section>

            <section id="features-section" class="py-20 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-10 text-[var(--text-primary)]">Todo lo que necesitas como fan</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                    <div class="bg-[var(--bg-secondary)] p-8 rounded-xl shadow-lg border border-[var(--border-color)]">
                        <svg fill="var(--text-primary)" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-6"><path d="M20 2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM4 4h16v16H4V4zm4 4h8v2H8V8zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"/></svg>
                        <h3 class="text-xl font-bold text-[var(--text-primary)] mb-3">CATALOGA</h3>
                        <p class="text-[var(--text-secondary)]">Crea tu biblioteca personal. Nunca olvides un juego completado o un anime visto.</p>
                    </div>
                    <div class="bg-[var(--bg-secondary)] p-8 rounded-xl shadow-lg border border-[var(--border-color)]">
                        <svg fill="var(--text-primary)" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-6"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        <h3 class="text-xl font-bold text-[var(--text-primary)] mb-3">CONECTA</h3>
                        <p class="text-[var(--text-secondary)]">Sigue a tus amigos, descubre qué están jugando y comparte tus opiniones.</p>
                    </div>
                    <div class="bg-[var(--bg-secondary)] p-8 rounded-xl shadow-lg border border-[var(--border-color)]">
                        <svg fill="var(--text-primary)" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-6"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                        <h3 class="text-xl font-bold text-[var(--text-primary)] mb-3">DESCUBRE</h3>
                        <p class="text-[var(--text-secondary)]">Encuentra tu próxima obsesión basándote en los gustos de la comunidad.</p>
                    </div>
                </div>
            </section>

            @guest 
                <section class="py-20 bg-[var(--bg-secondary)] text-center rounded-xl mt-10 shadow-lg border border-[var(--border-color)]">
                    <h2 class="text-3xl font-bold mb-10 text-[var(--text-primary)]">Echa un vistazo por dentro</h2>
                    <div class="max-w-xl mx-auto space-y-5 px-4">
                        @forelse ($recentActivities as $activity)
                            <div class="flex items-center gap-4 bg-[var(--bg-primary)] p-4 rounded-xl text-left shadow-md border border-[var(--border-color)]">
                                <img class="h-8 w-8 rounded-full object-cover flex-shrink-0"
                                    src="{{ $activity->user && $activity->user->avatar_path ? asset('storage/' . $activity->user->avatar_path) : asset('images/default-avatar.png') }}" 
                                    alt="Avatar de {{ $activity->user ? $activity->user->name : 'Usuario anónimo' }}">
                                
                                <div class="flex-grow">
                                    @if ($activity->subject && $activity->user)
                                        <p class="text-white text-base">
                                            <span class="font-semibold text-[var(--text-primary)]">{{ $activity->user->name }}</span>
                                            @if ($activity->type === 'created_post')
                                                ha publicado: <span class="font-medium text-[var(--text-secondary)]">"{{ Str::limit($activity->subject->title, 50) }}"</span>
                                            @elseif ($activity->type === 'updated_list_item' && $activity->subject instanceof \App\Models\Item)
                                                ha actualizado <span class="font-medium text-[var(--text-secondary)]">"{{ Str::limit($activity->subject->title, 50) }}"</span> en su lista.
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    @else
                                        <p class="text-gray-400 italic">Actividad de un usuario anónimo.</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-10 bg-[var(--bg-primary)] rounded-xl shadow-lg border border-[var(--border-color)]">
                                <p class="text-white text-xl font-semibold mb-4">¡El QuestLog te espera!</p>
                                <p class="text-gray-300">Sé el primero en empezar tu propia aventura. Aquí verás las últimas hazañas de otros usuarios.</p>
                            </div>
                        @endforelse
                    </div>
                    <p class="text-lg font-bold mt-12 text-white">Únete a más de 5.000 usuarios que ya organizan su pasión.</p>
                </section>
            @endguest

            <section class="py-20 text-center">
                <h2 class="text-3xl md:text-5xl font-extrabold mb-8 text-white">¿Listo para empezar?</h2>
                <a href="{{ route('register') }}" class="cta-button text-xl px-10 py-5">Empieza tu Aventura - Regístrate Ahora</a>
            </section>
        </main>
    </div> {{-- Fin del contenedor global de la landing --}}
    
    @stack('scripts')
    
</body>
</html>