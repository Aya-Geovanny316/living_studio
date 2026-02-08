<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotions = [
            [
                'title' => 'Coleccionistas 1:12',
                'subtitle' => 'Ediciones limitadas y preventas',
                'image_path' => '/brand/promos/promo-01.svg',
                'sort_order' => 1,
            ],
            [
                'title' => 'Semana del modelismo',
                'subtitle' => 'Kits y herramientas con descuento',
                'image_path' => '/brand/promos/promo-02.svg',
                'sort_order' => 2,
            ],
            [
                'title' => 'Pinturas y weathering',
                'subtitle' => 'Acabados realistas para tus piezas',
                'image_path' => '/brand/promos/promo-03.svg',
                'sort_order' => 3,
            ],
            [
                'title' => 'RC para terrenos extremos',
                'subtitle' => 'Drift, crawler y accesorios',
                'image_path' => '/brand/promos/promo-04.svg',
                'sort_order' => 4,
            ],
        ];

        foreach ($promotions as $promo) {
            Promotion::updateOrCreate(
                ['title' => $promo['title']],
                [
                    'subtitle' => $promo['subtitle'],
                    'image_path' => $promo['image_path'],
                    'sort_order' => $promo['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
