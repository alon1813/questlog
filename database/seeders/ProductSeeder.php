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
        //
        Schema::disableForeignKeyConstraints(); // <-- DESACTIVA LAS REGLAS
        Product::truncate();                    // <-- AHORA SÍ FUNCIONARÁ
        Schema::enableForeignKeyConstraints();  // <-- REACTIVA LAS REGLAS

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
            'affiliate_url' => '#', // Enlace de afiliado de Amazon, etc.
            'category' => 'Figuras',
        ]);

        Product::create([
            'name' => 'Figura Gojo Satoru',
            'price' => 39.95,
            'image_url' => 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcS8P4MzL_bTOl25EKcgzLaPmv1FFGhb91L5f8G6ivUPU8svonHF--fz5qRPOMHEEaKE7oTiAoJkCFnplUxmZzUcGNrzSJ96ap1xNgB1rGY0&usqp=CAc',
            'affiliate_url' => '#',
            'category' => 'Figuras',
        ]);
    }
}
