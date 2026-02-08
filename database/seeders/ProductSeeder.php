<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $products = [
            [
                'name' => 'Figura 1:12 heroe tactico',
                'category' => 'figuras-de-accion',
                'short_description' => 'Articulaciones premium y accesorios intercambiables.',
                'price_estimate' => 249.00,
                'images' => ['/brand/placeholders/product-01.svg'],
                'specs' => ['Escala' => '1:12', 'Altura' => '15 cm', 'Articulaciones' => '30+'],
            ],
            [
                'name' => 'Figura 1:10 villano deluxe',
                'category' => 'figuras-de-accion',
                'short_description' => 'Capa texturizada y base de exhibicion.',
                'price_estimate' => 329.00,
                'images' => ['/brand/placeholders/product-02.svg'],
                'specs' => ['Escala' => '1:10', 'Altura' => '18 cm', 'Material' => 'PVC premium'],
            ],
            [
                'name' => 'Modelo deportivo clasico 1:24',
                'category' => 'modelos-a-escala',
                'short_description' => 'Carroceria detallada con calcomanias.',
                'price_estimate' => 189.00,
                'images' => ['/brand/placeholders/product-03.svg'],
                'specs' => ['Escala' => '1:24', 'Piezas' => '120', 'Nivel' => 'Intermedio'],
            ],
            [
                'name' => 'Modelo camion clasico 1:32',
                'category' => 'modelos-a-escala',
                'short_description' => 'Cabina detallada y ruedas realistas.',
                'price_estimate' => 149.00,
                'images' => ['/brand/placeholders/product-04.svg'],
                'specs' => ['Escala' => '1:32', 'Piezas' => '80', 'Nivel' => 'Basico'],
            ],
            [
                'name' => 'Kit mecha 1:144',
                'category' => 'kits-de-armado',
                'short_description' => 'Armado sin pegamento, ideal para comenzar.',
                'price_estimate' => 119.00,
                'images' => ['/brand/placeholders/product-05.svg'],
                'specs' => ['Escala' => '1:144', 'Piezas' => '140', 'Herramientas' => 'Incluidas'],
            ],
            [
                'name' => 'Kit avion caza 1:72',
                'category' => 'kits-de-armado',
                'short_description' => 'Paneles grabados y cabina detallada.',
                'price_estimate' => 159.00,
                'images' => ['/brand/placeholders/product-06.svg'],
                'specs' => ['Escala' => '1:72', 'Piezas' => '95', 'Nivel' => 'Intermedio'],
            ],
            [
                'name' => 'Set diorama urbano',
                'category' => 'dioramas-y-accesorios',
                'short_description' => 'Calles, postes y anuncios para escenas.',
                'price_estimate' => 219.00,
                'images' => ['/brand/placeholders/product-01.svg'],
                'specs' => ['Escala' => '1:12', 'Piezas' => '20', 'Material' => 'Resina'],
            ],
            [
                'name' => 'Base display acrilica 1:12',
                'category' => 'dioramas-y-accesorios',
                'short_description' => 'Eleva tus figuras con soporte estable.',
                'price_estimate' => 59.00,
                'images' => ['/brand/placeholders/product-02.svg'],
                'specs' => ['Escala' => '1:12', 'Material' => 'Acrilico', 'Incluye' => '2 bases'],
            ],
            [
                'name' => 'Pinzas de precision pro',
                'category' => 'herramientas',
                'short_description' => 'Punta fina para piezas pequenas.',
                'price_estimate' => 49.00,
                'images' => ['/brand/placeholders/product-03.svg'],
                'specs' => ['Material' => 'Acero', 'Uso' => 'Detalle', 'Anti desliz' => 'Si'],
            ],
            [
                'name' => 'Alicate corte sprue',
                'category' => 'herramientas',
                'short_description' => 'Cortes limpios sin maltratar piezas.',
                'price_estimate' => 89.00,
                'images' => ['/brand/placeholders/product-04.svg'],
                'specs' => ['Corte' => 'Fino', 'Material' => 'Acero', 'Uso' => 'Modelismo'],
            ],
            [
                'name' => 'Set pinturas acrilicas 12 colores',
                'category' => 'pinturas-y-weathering',
                'short_description' => 'Paleta completa para figuras y maquetas.',
                'price_estimate' => 129.00,
                'images' => ['/brand/placeholders/product-05.svg'],
                'specs' => ['Contenido' => '12 x 17ml', 'Base' => 'Acrilica', 'Acabado' => 'Mate'],
            ],
            [
                'name' => 'Wash weathering oscuro',
                'category' => 'pinturas-y-weathering',
                'short_description' => 'Sombras profundas para paneles.',
                'price_estimate' => 39.00,
                'images' => ['/brand/placeholders/product-06.svg'],
                'specs' => ['Contenido' => '35ml', 'Uso' => 'Paneles', 'Secado' => 'Rapido'],
            ],
            [
                'name' => 'Coleccionable premium 1:6',
                'category' => 'coleccionables-premium',
                'short_description' => 'Edicion limitada con base numerada.',
                'price_estimate' => 1599.00,
                'images' => ['/brand/placeholders/product-01.svg'],
                'specs' => ['Escala' => '1:6', 'Altura' => '30 cm', 'Edicion' => 'Limitada'],
            ],
            [
                'name' => 'Estatua resina 1:4',
                'category' => 'coleccionables-premium',
                'short_description' => 'Pieza de exhibicion con detalle extremo.',
                'price_estimate' => 2499.00,
                'images' => ['/brand/placeholders/product-02.svg'],
                'specs' => ['Escala' => '1:4', 'Altura' => '48 cm', 'Material' => 'Resina'],
            ],
            [
                'name' => 'Auto RC drift 1:16',
                'category' => 'rc-y-modelismo',
                'short_description' => 'Traccion precisa para derrapes controlados.',
                'price_estimate' => 359.00,
                'images' => ['/brand/placeholders/product-03.svg'],
                'specs' => ['Escala' => '1:16', 'Velocidad' => '25 km/h', 'Bateria' => '7.4V'],
            ],
            [
                'name' => 'Crawler RC 1:18',
                'category' => 'rc-y-modelismo',
                'short_description' => 'Suspension flexible para terrenos dificiles.',
                'price_estimate' => 429.00,
                'images' => ['/brand/placeholders/product-04.svg'],
                'specs' => ['Escala' => '1:18', 'Traccion' => '4x4', 'Bateria' => '7.4V'],
            ],
        ];

        foreach ($products as $index => $product) {
            $category = $categories[$product['category']] ?? null;
            if (! $category) {
                continue;
            }

            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                [
                    'category_id' => $category->id,
                    'name' => $product['name'],
                    'slug' => Str::slug($product['name']),
                    'short_description' => $product['short_description'],
                    'description' => $product['short_description'],
                    'price_estimate' => $product['price_estimate'],
                    'images' => $product['images'],
                    'specs' => $product['specs'],
                    'featured' => $index < 4,
                    'is_active' => true,
                ]
            );
        }
    }
}
