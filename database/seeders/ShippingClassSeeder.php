<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'light',
                'min_units' => 1,
                'max_units' => 4,
                'load_factor' => 1.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'medium',
                'min_units' => 5,
                'max_units' => 10,
                'load_factor' => 1.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'heavy',
                'min_units' => 11,
                'max_units' => 20,
                'load_factor' => 2.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'extra_heavy',
                'min_units' => 21,
                'max_units' => 100,
                'load_factor' => 3.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('shipping_classes')->upsert(
            $data,
            ['name'], // unique key
            ['min_units', 'max_units', 'load_factor', 'updated_at']
        );
    }
}
