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
            'image_url' => 'https://m.media-amazon.com/images/I/71d-H4vKj9L._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);

        Product::create([
            'name' => 'Mando Xbox Series X|S - Carbon Black',
            'price' => 59.99,
            'image_url' => 'https://m.media-amazon.com/images/I/61G5yL-s-OL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);

        Product::create([
            'name' => 'Figura Luffy Gear 5',
            'price' => 69.99,
            'image_url' => 'https://m.media-amazon.com/images/I/61qS02q70ZL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Mochila Pokémon Pikachu',
            'price' => 29.99,
            'image_url' => 'https://m.media-amazon.com/images/I/61-mS4m1YdL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Taza The Legend of Zelda: Triforce',
            'price' => 14.99,
            'image_url' => 'https://m.media-amazon.com/images/I/61k2Xp3eFjL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Funko Pop! Geralt of Rivia',
            'price' => 12.99,
            'image_url' => 'https://m.media-amazon.com/images/I/61Nl-5tP1XL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Camiseta Cyberpunk 2077 - Samurai',
            'price' => 24.99,
            'image_url' => 'https://m.media-amazon.com/images/I/61D79f22NUL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Ropa',
        ]);

        Product::create([
            'name' => 'Alfombrilla de Ratón Grande - Mapa de Hyrule',
            'price' => 19.99,
            'image_url' => 'https://m.media-amazon.com/images/I/710gG-QeMmL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);

        Product::create([
            'name' => 'Libro de Arte de Elden Ring Vol. 1',
            'price' => 35.00,
            'image_url' => 'https://m.media-amazon.com/images/I/81f-g8j-yCL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Libros',
        ]);

        Product::create([
            'name' => 'Funda Nintendo Switch - Animal Crossing',
            'price' => 19.99,
            'image_url' => 'https://m.media-amazon.com/images/I/71Y8K9X3YqL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);

        Product::create([
            'name' => 'Figura Nendoroid Megumin',
            'price' => 75.00,
            'image_url' => 'https://m.media-amazon.com/images/I/61jC8Vq2SUL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Póster One Piece - Wanted Luffy',
            'price' => 9.99,
            'image_url' => 'https://m.media-amazon.com/images/I/71P4zY50j2L._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Camiseta Gaming - "Eat Sleep Game Repeat"',
            'price' => 19.99,
            'image_url' => 'https://m.media-amazon.com/images/I/61H4L-u-YEL._AC_UL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Ropa',
        ]);

        Product::create([
            'name' => 'Llavero de Metal - Espada Maestra',
            'price' => 7.99,
            'image_url' => 'https://m.media-amazon.com/images/I/51b7Z9Q3sYL._AC_SL1000_.jpg',
            'affiliate_url' => '#',
            'category' => 'Accesorios',
        ]);

        Product::create([
            'name' => 'Mochila Jujutsu Kaisen',
            'price' => 34.99,
            'image_url' => 'https://m.media-amazon.com/images/I/71b2gL0q8QL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Reloj de Pared - Pac-Man',
            'price' => 25.00,
            'image_url' => 'https://m.media-amazon.com/images/I/71m4e9lP0mL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Decoración',
        ]);

        Product::create([
            'name' => 'Puzzle 1000 Piezas - Witcher 3',
            'price' => 22.99,
            'image_url' => 'https://m.media-amazon.com/images/I/81B4W1B9kSL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Juegos de Mesa',
        ]);

        Product::create([
            'name' => 'Gorra con Logo de PlayStation',
            'price' => 16.99,
            'image_url' => 'https://m.media-amazon.com/images/I/71N7M2Y4xUL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Ropa',
        ]);

        Product::create([
            'name' => 'Cojín Demon Slayer - Nezuko',
            'price' => 20.00,
            'image_url' => 'https://m.media-amazon.com/images/I/61R+g4Q9YxL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Decoración',
        ]);

        Product::create([
            'name' => 'Figura Pop! Pokémon - Bulbasaur',
            'price' => 15.00,
            'image_url' => 'https://m.media-amazon.com/images/I/611D3lD6gDL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Manga My Hero Academia Vol. 1',
            'price' => 8.99,
            'image_url' => 'https://m.media-amazon.com/images/I/81xUe5-rCmL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Libros',
        ]);

        Product::create([
            'name' => 'Botella de Agua - Super Mario',
            'price' => 11.99,
            'image_url' => 'https://m.media-amazon.com/images/I/71+0j7R9p9L._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Merchandising',
        ]);

        Product::create([
            'name' => 'Figura Nendoroid Eren Yeager',
            'price' => 85.00,
            'image_url' => 'https://m.media-amazon.com/images/I/618x7C-9tAL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Puzzle 500 Piezas - Dragon Ball Z',
            'price' => 18.99,
            'image_url' => 'https://m.media-amazon.com/images/I/81w+9y0p7AL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Juegos de Mesa',
        ]);

        Product::create([
            'name' => 'Manta Polar - Spider-Man',
            'price' => 29.99,
            'image_url' => 'https://m.media-amazon.com/images/I/71x4xS+u8GL._AC_SL1500_.jpg',
            'affiliate_url' => '#',
            'category' => 'Hogar',
        ]);

        Product::create([
            'name' => 'Taza Genshin Impact - Paimon',
            'price' => 16.00,
            'image_url' => 'https://m.media-amazon.com/images/I/61s0B4pL-zL._AC_SL1500_.jpg',
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