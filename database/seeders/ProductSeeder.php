<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Elektronik' => [
                ['name' => 'Laptop Pro Ultra 15', 'price' => 15499000, 'stock' => 15, 'desc' => 'Laptop bertenaga tinggi untuk profesional dan kreator konten.'],
                ['name' => 'Mechanical Keyboard RGB', 'price' => 899000, 'stock' => 30, 'desc' => 'Keyboard mekanik dengan switch tactile dan pencahayaan RGB dinamis.'],
                ['name' => 'Wireless Gaming Mouse', 'price' => 450000, 'stock' => 25, 'desc' => 'Mouse presisi tinggi dengan sensor optik 16000 DPI tanpa kabel.'],
                ['name' => 'Monitor Gaming 27" 165Hz', 'price' => 3299000, 'stock' => 10, 'desc' => 'Layar IPS jernih dengan refresh rate 165Hz untuk pengalaman visual maksimal.'],
            ],
            'Pakaian & Fashion' => [
                ['name' => 'Jaket Hoodie Minimalis', 'price' => 249000, 'stock' => 50, 'desc' => 'Bahan fleece tebal dan lembut, nyaman untuk aktivitas sehari-hari.'],
                ['name' => 'Kaos Polos Cotton Combed 30s', 'price' => 75000, 'stock' => 100, 'desc' => 'Kaos katun premium yang sejuk dan menyerap keringat dengan sempurna.'],
                ['name' => 'Celana Chino Slim Fit', 'price' => 195000, 'stock' => 40, 'desc' => 'Celana panjang chino bergaya modern untuk santai maupun kerja.'],
            ],
            'Aksesoris' => [
                ['name' => 'Smartwatch Fitness Tracker', 'price' => 699000, 'stock' => 20, 'desc' => 'Pantau detak jantung, pola tidur, dan langkah harian Anda dengan mudah.'],
                ['name' => 'Tas Ransel Anti Air', 'price' => 320000, 'stock' => 35, 'desc' => 'Ransel ergonomis dengan kompartemen laptop dan bahan tahan air.'],
                ['name' => 'Kacamata Anti Radiasi UV400', 'price' => 125000, 'stock' => 60, 'desc' => 'Melindungi mata dari paparan sinar biru layar komputer dan smartphone.'],
            ],
        ];

        foreach ($categories as $catName => $products) {
            $cat = Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName]
            );

            foreach ($products as $prod) {
                $product = Product::firstOrCreate(
                    ['slug' => Str::slug($prod['name'])],
                    [
                        'category_id' => $cat->id,
                        'name' => $prod['name'],
                        'description' => $prod['desc'],
                        'price' => $prod['price'],
                        'stock' => $prod['stock'],
                        'is_active' => true,
                    ]
                );

                if ($product->images()->count() === 0) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'https://picsum.photos/seed/' . $product->slug . '/600/400',
                        'is_primary' => true,
                    ]);
                }
            }
        }
    }
}
