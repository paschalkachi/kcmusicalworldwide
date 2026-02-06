<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class BackfillCategoryCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            // Generate base code: first 3 letters uppercase
            $base = strtoupper(substr($category->name, 0, 3));

            // Count existing codes with same base to ensure uniqueness
            $count = Category::where('code', 'like', $base . '%')->count() + 1;

            // Final unique code (e.g., ELE01, FAS01)
            $category->code = $base . str_pad($count, 2, '0', STR_PAD_LEFT);

            $category->save();

            echo "Category {$category->name} => Code {$category->code}\n";
        }
    }
}
