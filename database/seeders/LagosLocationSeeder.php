<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LagosLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Ikeja', 'shipping_price' => 2000],
            ['name' => 'Yaba', 'shipping_price' => 1800],
            ['name' => 'Surulere', 'shipping_price' => 2200],
            ['name' => 'Lekki Phase 1', 'shipping_price' => 3000],
            ['name' => 'Ajah', 'shipping_price' => 2800],
            ['name' => 'Ikorodu', 'shipping_price' => 3500],
            ['name' => 'Agege', 'shipping_price' => 2300],
            ['name' => 'Alimosho', 'shipping_price' => 2600],
            ['name' => 'Oshodi', 'shipping_price' => 2400],
            ['name' => 'Victoria Island', 'shipping_price' => 3200],
        ];

        foreach ($locations as $location) {
            DB::table('lagos_locations')->insert([
                'name' => $location['name'],
                'shipping_price' => $location['shipping_price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
