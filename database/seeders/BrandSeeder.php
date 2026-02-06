<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'H-D Hydinamics',
            'Yamaha',
            'Shure',
            'Behringer',
            'Samson',
            'Mackie',
            'Infinity',
            'Ibanez',
            'Sound Prince',
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['slug' => Str::slug($brand)],
                [
                    'name' => $brand,
                ]
            );
        }
    }
}
