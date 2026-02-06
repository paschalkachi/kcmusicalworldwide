<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Speakers',
            'Public Address System',
            'Mixers',
            'Power Amplifier',
            'Powered Mixers',
            'Microphones',
            'Guitars',
            'Trumpet',
            'Saxophone',
            'Drum Set',
            'Studio Equipment',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category)],
                [
                    'name' => $category,
                ]
            );
        }
    }
}
