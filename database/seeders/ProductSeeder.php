<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints(); // <-- DESACTIVA LAS REGLAS DE CLAVE FORÁNEA
        Product::truncate();                    // <-- VACÍA LA TABLA DE PRODUCTOS
        Schema::enableForeignKeyConstraints();  // <-- REACTIVA LAS REGLAS

        // Productos existentes
        Product::create([
            'name' => 'Nintendo Switch OLED',
            'price' => 349.99,
            'image_url' => 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQElmo1mIW3oIIYpLfg_biCa_ZgDoRxdXjd-9tsqHDYy5emhpmha1IXOK9oTSsuieFD4PTH3uftNpJDkEKwX46yQq3khz3qGrlRu7LKjD4njkxZLjG6DbAcNkidU5zMBzPGoXNesg&usqp=CAc',
            'affiliate_url' => 'https://example.com/affiliate/switch_oled',
            'category' => 'Consolas'
        ]);

        Product::create([
            'name' => 'PlayStation 5',
            'price' => 499.99,
            'image_url' => 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSEUKhBiEp6quPB85irgBr-CheJkBZ2-i_Gq8DzGQOq7m01FBrEMmcm5qVFn2MtLBLj--SSOetxP8MfIJFWBiuA2BciCMShhKjuAVyXDTJMsgtRZK3cOPIiDSuIMVrqV-3Cp_Z3pRA&usqp=CAc',
            'affiliate_url' => 'https://example.com/affiliate/ps5',
            'category' => 'Consolas'
        ]);

        Product::create([
            'name' => 'Figura V - Cyberpunk 2077',
            'price' => 49.99,
            'image_url' => 'https://static.xtralife.com/conversions/Y3C1-WJ71315899-fullhd_w1920_h1080_q75-figuramalevcp7700-1617111807.webp',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Figura Gojo Satoru',
            'price' => 39.95,
            'image_url' => 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcS8P4MzL_bTOl25EKcgzLaPmv1FFGhb91L5f8G6ivUPU8svonHF--fz5qRPOMHEEaKE7oTiAoJkCFnplUxmZzUcGNrzSJ96ap1xNgB1rGY0&usqp=CAc',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        // Productos adicionales que me has proporcionado
        Product::create([
            'name' => 'Auriculares Gaming HyperX Cloud II',
            'price' => 79.99,
            'image_url' => 'https://row.hyperx.com/cdn/shop/files/hyperx_cloud_ii_red_1_main.jpg?v=1737720332',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);

        Product::create([
            'name' => 'Mando Xbox Series X|S - Carbon Black',
            'price' => 59.99,
            'image_url' => 'https://m.media-amazon.com/images/I/71jje-vi17L._AC_UF894,1000_QL80_.jpg',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);

        Product::create([
            'name' => 'Figura Luffy Gear 5',
            'price' => 69.99,
            'image_url' => 'https://sunnystore.es/cdn/shop/files/megahouse-225118-one-piece-monkey-d-luffy-gear-5-p-o-p-2413cm.jpg?v=1723222457',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Mochila Pokémon Pikachu',
            'price' => 29.99,
            'image_url' => 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRsAhKXge4zCzeOzL1KD8rTHl7yP0c4JRhTDYRrBgdOXYCccqP2xhzYUXhFL1f8wrJBsAexG63qJorDGX3rXj4vJ-gaovV-DYwf6H3YXQNtYt3WeHCFsOHMt-AR7w&usqp=CAc',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Taza The Legend of Zelda: Triforce',
            'price' => 14.99,
            'image_url' => 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcSHyoheTaIdIq0ywEuAZaRWQPMiFjTGtnm553UrTSLtzKWagbp_6XFWWtCLKn9NNXsOETWDhLQzzJol8EUC5-nckDKHdDwKeIU8mnh90d9Y5yS-L0vKL_9te2rMZV7qRttmVYgvUAY&usqp=CAc',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Funko Pop! Geralt of Rivia',
            'price' => 12.99,
            'image_url' => 'https://m.media-amazon.com/images/I/61KnYZ0KokL.jpg',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Camiseta Cyberpunk 2077 - Samurai',
            'price' => 24.99,
            'image_url' => 'https://www.emp-online.es/dw/image/v2/BBQV_PRD/on/demandware.static/-/Sites-master-emp/default/dwf438ca79/images/5/8/5/2/585254a.jpg?sfrm=png',
            'affiliate_url' => '#',
            'category' => 'Ropa',
        ]);

        Product::create([
            'name' => 'Alfombrilla de Ratón Grande - Mapa de Hyrule',
            'price' => 19.99,
            'image_url' => 'https://m.media-amazon.com/images/I/71xuSR0nYeL._AC_UF894,1000_QL80_.jpg',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);

        Product::create([
            'name' => 'Libro de Arte de Elden Ring Vol. 1',
            'price' => 35.00,
            'image_url' => 'https://m.media-amazon.com/images/I/71xxdi7pifL.jpg_BO30,255,255,255_UF900,850_SR1910,1000,0,C_PIRIOFOUR-medium,BottomLeft,30,-20_ZJPHNwYW4gZm9yZWdyb3VuZD0iIzU2NTk1OSIgZm9udD0iQW1hem9uRW1iZXIgNTAiID4yODwvc3Bhbj4=,500,900,420,420,0,0_QL100_.jpg',
            'affiliate_url' => '#',
            'category' => 'Libros',
        ]);

        Product::create([
            'name' => 'Funda Nintendo Switch - Animal Crossing',
            'price' => 19.99,
            'image_url' => 'https://kareenaelectronics.com/10939-large_default/game-traveler-deluxe-travel-case-funda-switch-animal-crossing-nns39ac.jpg',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);

        Product::create([
            'name' => 'Figura Nendoroid Megumin',
            'price' => 75.00,
            'image_url' => 'https://m.media-amazon.com/images/I/51QiAFY4j5L._AC_UF894,1000_QL80_.jpg',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Póster One Piece - Wanted Luffy',
            'price' => 9.99,
            'image_url' => 'https://m.media-amazon.com/images/I/71KcjFwSJTL._AC_UF894,1000_QL80_.jpg',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Camiseta Gaming - "Eat Sleep Game Repeat"',
            'price' => 19.99,
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQuoWcu9jRIqvybB_SJekMmeymhMGKqAB4Mig&s',
            'affiliate_url' => '#',
            'category' => 'Ropa',
        ]);

        Product::create([
            'name' => 'Llavero de Metal - Espada Maestra',
            'price' => 7.99,
            'image_url' => 'https://m.media-amazon.com/images/I/51cqzYMnkRL._AC_UY1000_.jpg',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);

        Product::create([
            'name' => 'Mochila Jujutsu Kaisen',
            'price' => 34.99,
            'image_url' => 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTxGRyHQkX0CTE7wScz9jHaI6MnnT3CQaJJ9kyF_7bRExHQZLddgGboNpljJCkzNHDUV8k_8zVHez5QbCNgBX1__HWmpRNkZipUbdmyV5Xt63lpNAjPK4C5lVyOCA&usqp=CAc',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Reloj de Pared - Pac-Man',
            'price' => 25.00,
            'image_url' => 'https://images-na.ssl-images-amazon.com/images/I/71k5ZzUY0LL._AC_UL495_SR435,495_.jpg',
            'affiliate_url' => '#',
            'category' => 'Decoración',
        ]);

        Product::create([
            'name' => 'Puzzle 1000 Piezas - Witcher 3',
            'price' => 22.99,
            'image_url' => 'https://www.somosjuegos.com/wp-content/uploads/2025/04/The-Witcher-Puzzle-1000-3.jpg',
            'affiliate_url' => '#',
            'category' => 'Juegos de Mesa',
        ]);

        Product::create([
            'name' => 'Gorra con Logo de PlayStation',
            'price' => 16.99,
            'image_url' => 'https://sw6.elbenwald.de/media/c8/c1/47/1629829124/E1045906_1.jpg',
            'affiliate_url' => '#',
            'category' => 'Ropa',
        ]);

        Product::create([
            'name' => 'Cojín Demon Slayer - Nezuko',
            'price' => 20.00,
            'image_url' => 'https://m.media-amazon.com/images/I/719losJp+rL.jpg',
            'affiliate_url' => '#',
            'category' => 'Decoración',
        ]);

        Product::create([
            'name' => 'Figura Pop! Pokémon - Bulbasaur',
            'price' => 15.00,
            'image_url' => 'https://m.media-amazon.com/images/I/61vl9iIVNoL.jpg',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Manga My Hero Academia Vol. 1',
            'price' => 8.99,
            'image_url' => 'https://m.media-amazon.com/images/I/61s2nF5TMVL.jpg',
            'affiliate_url' => '#',
            'category' => 'Libros',
        ]);

        Product::create([
            'name' => 'Botella de Agua - Super Mario',
            'price' => 11.99,
            'image_url' => 'https://laboutiquedestoons.com/44781-thickbox_default/super-mario-family-botella-de-agua-de-aluminio-600-ml.jpg',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Figura Nendoroid Eren Yeager',
            'price' => 85.00,
            'image_url' => 'https://m.media-amazon.com/images/I/61vok08gXFL.jpg',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Puzzle 500 Piezas - Dragon Ball Z',
            'price' => 18.99,
            'image_url' => 'https://m.media-amazon.com/images/I/81pnAzCs60L.jpg',
            'affiliate_url' => '#',
            'category' => 'Juegos de Mesa',
        ]);

        Product::create([
            'name' => 'Manta Polar - Spider-Man',
            'price' => 29.99,
            'image_url' => 'https://m.media-amazon.com/images/I/61Eo4EYxJGL._AC_UF894,1000_QL80_.jpg',
            'affiliate_url' => '#',
            'category' => 'Hogar',
        ]);

        Product::create([
            'name' => 'Taza Genshin Impact - Paimon',
            'price' => 16.00,
            'image_url' => 'https://m.media-amazon.com/images/I/61BJ5SDin8L._AC_UF350,350_QL80_.jpg',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Teclado Mecánico Razer BlackWidow',
            'price' => 129.99,
            'image_url' => 'https://m.media-amazon.com/images/I/81bc8mA3nKL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);
    }
}