@props(['slides', 'title' => 'Elementos Populares'])

@push('styles')
    {{-- Cargamos estilos de Swiper v11 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        /* EL CONTENEDOR PRINCIPAL */
        .swiper {
            width: 100%;
            padding-top: 20px;
            padding-bottom: 50px;
        }

        /* LAS TARJETAS INDIVIDUALES */
        .swiper-slide {
            background-position: center;
            background-size: cover;
            width: 260px;  /* ANCHO FIJO IMPORTANTE */
            height: 360px; /* ALTO FIJO IMPORTANTE */
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
            /* Esto evita que se estiren */
            flex-shrink: 0;
        }
        
        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* TEXTO SOBRE LA IMAGEN */
        .slide-info {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 15px;
            background: linear-gradient(to top, black, transparent);
            color: white;
        }

        /* PAGINACIÓN */
        .swiper-pagination-bullet { background: rgba(255,255,255,0.5); }
        .swiper-pagination-bullet-active { background: white; }
    </style>
@endpush

<div class="relative">
    @if($title)
        <h3 class="text-3xl font-bold mb-8 text-white text-center">{{ $title }}</h3>
    @endif

    <div class="swiper">
        <div class="swiper-wrapper">
            @foreach ($slides as $slide)
                <div class="swiper-slide">
                    <a href="{{ $slide->link ?? '#' }}" class="block w-full h-full relative">
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}">
                        <div class="slide-info">
                            <h4 class="text-lg font-bold">{{ $slide->title }}</h4>
                            <span class="text-xs uppercase text-gray-300">{{ $slide->type }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        
        <div class="swiper-pagination"></div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiperElement = document.querySelector('.swiper');
            if(swiperElement) {
                new Swiper(swiperElement, {
                    effect: 'coverflow',
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto', // ESTO USA EL ANCHO DE CSS (260px)
                    coverflowEffect: {
                        rotate: 0,
                        stretch: 0,
                        depth: 100,
                        modifier: 2,
                        slideShadows: true,
                    },
                    loop: true, // Bucle infinito
                    autoplay: {
                        delay: 2500,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    }
                });
            }
        });
    </script>
@endpush