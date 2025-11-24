<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Landing - QuestLog</title>
    
    <link rel="icon" type="image/png" href="{{ asset('favicons/source/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicons/source/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicons/source/favicon.ico') }}" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        .landing-swiper {
            width: 100%;
            padding-top: 20px;
            padding-bottom: 50px;
            overflow: hidden; 
        }
        
        .landing-slide {
            background-position: center;
            background-size: cover;
            width: 220px; 
            height: 320px; 
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.6);
            border: 1px solid rgba(255,255,255,0.1);
            transition: transform 0.3s ease;
        }

        .landing-slide:hover {
            border-color: rgba(255,255,255,0.5);
        }

        .landing-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slide-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, transparent 100%);
            color: white;
        }
        
        
        .swiper-pagination-bullet { background: rgba(255,255,255,0.4) !important; }
        .swiper-pagination-bullet-active { background: white !important; }
    </style>
</head>

<body class="bg-gray-900 text-gray-300 font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="flex justify-between items-center py-6 border-b border-gray-800">
            <div class="text-2xl font-black text-white tracking-tighter flex items-center gap-2">
                QuestLog
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="#features" class="font-medium hover:text-white transition">Características</a>
                <a href="{{ route('posts.index') }}" class="font-medium hover:text-white transition">Blog</a>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="font-medium hover:text-white transition">Entrar</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-full font-bold transition shadow-lg shadow-indigo-500/30">Registrarse</a>
            </div>
        </header>

        <main>
            
            <section class="text-center py-20 md:py-32 relative">
                
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full overflow-hidden -z-10 opacity-30 pointer-events-none">
                    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
                    <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
                </div>

                <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight tracking-tight">
                    Tu colección, <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">tu legado.</span>
                </h1>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                    La plataforma definitiva para catalogar tus juegos, animes y series. 
                    Comparte tu progreso y descubre tu próxima obsesión.
                </p>
                <a href="{{ route('register') }}" class="bg-white text-gray-900 px-8 py-4 rounded-full text-lg font-bold hover:bg-gray-100 transition inline-flex items-center gap-2 shadow-xl">
                    Comenzar Ahora
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
            </section>

            <section class="py-10 border-y border-gray-800 bg-gray-800/30 backdrop-blur-sm mb-24">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-white mb-2">Tendencias de la Comunidad</h2>
                    <p class="text-sm text-gray-400">Lo que se está jugando y viendo esta semana</p>
                </div>

                <div class="swiper landing-swiper">
                    <div class="swiper-wrapper">
                        @for ($i = 0; $i < 3; $i++) 
                            @foreach ($popularItems as $item)
                                <div class="swiper-slide landing-slide">
                                    <a href="{{ $item->link ?? '#' }}" class="block w-full h-full relative">
                                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
                                        <div class="slide-overlay">
                                            <h4 class="text-base font-bold leading-tight">{{ $item->title }}</h4>
                                            <span class="text-xs uppercase tracking-wider text-indigo-300">{{ $item->type }}</span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endfor
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </section>

            <section id="features" class="grid md:grid-cols-3 gap-8 text-center pb-24">
                <div class="p-8 bg-gray-800/50 rounded-2xl border border-gray-700 hover:border-indigo-500/50 transition group">
                    <div class="w-16 h-16 bg-indigo-900/50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Biblioteca Unificada</h3>
                    <p class="text-gray-400 leading-relaxed">Olvídate de las hojas de cálculo. Ten todos tus juegos, animes y series en un solo perfil elegante.</p>
                </div>
                <div class="p-8 bg-gray-800/50 rounded-2xl border border-gray-700 hover:border-purple-500/50 transition group">
                    <div class="w-16 h-16 bg-purple-900/50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Comunidad Activa</h3>
                    <p class="text-gray-400 leading-relaxed">Sigue a tus amigos, comenta sus progresos y compara vuestros gustos en tiempo real.</p>
                </div>
                <div class="p-8 bg-gray-800/50 rounded-2xl border border-gray-700 hover:border-pink-500/50 transition group">
                    <div class="w-16 h-16 bg-pink-900/50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Descubrimiento</h3>
                    <p class="text-gray-400 leading-relaxed">¿No sabes qué jugar? Nuestro algoritmo te sugiere joyas ocultas basadas en lo que ya te gusta.</p>
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

            <section class="py-20 text-center border-t border-gray-800">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-8 text-white">¿Listo para empezar tu aventura?</h2>
                <a href="{{ route('register') }}" class="cta-button text-xl px-12 py-5 bg-white text-gray-900 rounded-full font-bold hover:bg-gray-200 transition">Únete Gratis</a>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.landing-swiper', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto', 
                initialSlide: 2,       
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 150,        
                    modifier: 2.5,
                    slideShadows: true,
                },
                loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>