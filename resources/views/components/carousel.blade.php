@props(['slides', 'title' => 'Elementos Populares'])

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <style>
        /* Base para el contenedor Swiper */
        .swiper-container {
            width: 100%; /* Asegura que siempre ocupe el 100% del ancho disponible de su padre */
            height: auto;
            padding-bottom: 40px;
            position: relative;
            margin-left: auto; 
            margin-right: auto;
            overflow: hidden; /* ¡Forzar overflow hidden en el contenedor principal! */
        }

        .swiper-wrapper {
            position: relative; /* Esencial para el posicionamiento interno de Swiper */
            width: 100%; /* El wrapper debe ocupar el 100% del container */
            height: 100%; /* Y la altura completa */
            box-sizing: content-box; /* Asegura que padding no afecte el width total si se añade */
            display: flex; /* Asegura un layout flexible para los slides */
            z-index: 1; /* Asegura que esté por encima de elementos de fondo si los hubiera */
        }

        /* Estilos para cada slide */
        .swiper-slide {
            display: flex;
            flex-shrink: 0; /* Asegura que los slides no se encojan más de lo necesario */
            align-items: flex-start;
            justify-content: center;
            background: var(--bg-secondary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow: hidden; /* También en el slide por si acaso */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100% !important; /* ¡Forzar el ancho del slide a 100% de su espacio asignado por Swiper! */
            /* Aseguramos que el slide ocupe el ancho que Swiper le calcula, sin dejar "gaps" */
        }
        
        /* Asegurarse de que la imagen dentro del slide se vea bien */
        .swiper-slide img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .swiper-slide:hover img {
            transform: scale(1.05);
        }

        /* Título y tipo en el slide */
        .swiper-slide .absolute {
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
            padding: 1rem;
            color: white;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
            width: 100%;
        }

        /* Botones de navegación (flechas) */
        .swiper-button-prev,
        .swiper-button-next {
            color: var(--text-primary);
            width: 40px;
            height: 40px;
            background-color: rgba(0,0,0,0.5);
            border-radius: 50%;
            transition: background-color 0.3s ease;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 11; /* Un z-index más alto para estar seguros */
            cursor: pointer; /* Indica que es clickeable */
        }

        .swiper-button-prev:hover,
        .swiper-button-next:hover {
            background-color: rgba(0,0,0,0.8);
        }

        .swiper-button-prev::after,
        .swiper-button-next::after {
            font-size: 20px;
            font-weight: bold;
        }

        /* Ajustar la posición de las flechas para que estén más pegadas a los bordes del CONTENEDOR VISIBLE del carrusel,
           en lugar de al borde extremo del 'swiper-container' si este tiene mucho padding invisible. */
        .swiper-button-prev {
            left: 10px; /* Separación del borde visible */
        }

        .swiper-button-next {
            right: 10px; /* Separación del borde visible */
        }

        /* Paginación (los puntos) */
        .swiper-pagination {
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            text-align: center;
            z-index: 10;
        }

        .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.5);
            opacity: 1;
        }

        .swiper-pagination-bullet-active {
            background: var(--text-primary);
        }

        /* Ocultar el debugger de Swiper (las bolitas rojas) */
        .swiper-notification {
            display: none !important;
        }
    </style>
@endpush

<div class="swiper-container my-8">
    <h3 class="text-2xl font-bold mb-4 text-[var(--text-primary)]">{{ $title }}</h3>
    <div class="swiper-wrapper">
        @foreach ($slides as $slide)
            <div class="swiper-slide rounded-lg overflow-hidden relative group">
                <a href="{{ $slide->link ?? '#' }}" class="block w-full h-full"> {{-- Añadido w-full h-full --}}
                    <img src="{{ $slide->image_url }}" 
                        alt="{{ $slide->title }}" 
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"> {{-- h-full --}}
                    {{-- El gradiente y texto se superponen sobre la imagen --}}
                    <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black via-transparent to-transparent text-white">
                        <h4 class="text-lg font-semibold">{{ $slide->title }}</h4>
                        <span class="text-sm text-gray-300 capitalize">{{ $slide->type }}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- If you need pagination --}}
    <div class="swiper-pagination"></div>

    {{-- If you need navigation buttons --}}
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

@push('scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if Swiper is already initialized on a container, destroy and re-initialize if needed (for SPA/Turbo)
            const swiperContainers = document.querySelectorAll('.swiper-container');
            swiperContainers.forEach(container => {
                if (container.swiper) {
                    container.swiper.destroy(true, true);
                }

                new Swiper(container, {
                    direction: 'horizontal',
                    loop: true,
                    slidesPerView: 'auto', 
                    centeredSlides: true,
                    spaceBetween: 15, 
                    autoplay: { // Añadir autoplay opcional
                        delay: 5000,
                        disableOnInteraction: false,
                        stopOnLastSlide: false,
                    },
                    pagination: {
                        el: container.querySelector('.swiper-pagination'),
                        clickable: true,
                    },
                    navigation: {
                        nextEl: container.querySelector('.swiper-button-next'),
                        prevEl: container.querySelector('.swiper-button-prev'),
                    },
                    breakpoints: {
                        640: { slidesPerView: 'auto', spaceBetween: 15 },
                        768: { slidesPerView: 'auto', spaceBetween: 20 },
                        1024: { slidesPerView: 'auto', spaceBetween: 25 },
                    }
                });
            });
        });
    </script>
@endpush