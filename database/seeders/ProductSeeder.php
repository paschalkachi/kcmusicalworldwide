<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ShippingClass;
use App\Services\SkuGenerator;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Yamaha PSR-E473 Keyboard',
                'short_description' => '61-key portable keyboard with 820 voices and 290 styles.',
                'description' => 'Yamaha PSR-E473 is a versatile 61-key portable keyboard with touch-sensitive keys, packed with 820 high-quality voices and 290 auto-accompaniment styles for diverse musical genres, featuring professional tools like Live Control Knobs, Groove Creator, Quick Sampling, USB Audio Recorder, and a microphone input with vocal effects, making it great for beginners and intermediate players.',
                'regular_price' => 450000,
                'stock_status' => 'instock',
                'featured' => 1,
                'quantity' => 10,
                'preorder_limit' => null,
                'category' => 'Powered Mixers',
                'brand' => 'Yamaha',
                'shipping_class' => 'light',
                'shipping_unit' => 1,
            ],
            [
                'name' => 'Yamaha P7000S Power Amplifier',
                'short_description' => '2-channel Class AB power amplifier for live sound.',
                'description' => 'Yamaha P7000S is a powerful, budget-friendly 2-channel Class AB power amplifier designed for live sound, featuring 700W per channel, integrated DSP, EEEngine technology, flexible Neutrik outputs, balanced XLR inputs, and robust protection circuits.',
                'regular_price' => 250000,
                'stock_status' => 'instock',
                'featured' => 1,
                'quantity' => 5,
                'preorder_limit' => null,
                'category' => 'Power Amplifier',
                'brand' => 'Yamaha',
                'shipping_class' => 'medium',
                'shipping_unit' => 5,
            ],
            [
                'name' => 'Yamaha 5-piece drum set',
                'short_description' => 'Standard 5-piece drum set with quality shells.',
                'description' => 'Typical Yamaha 5-piece drum set includes bass drum, snare, 2 rack toms, and floor tom, often with birch or poplar shells, YESS tom mounts, low-mass lugs.',
                'regular_price' => 250000,
                'stock_status' => 'instock',
                'featured' => 0,
                'quantity' => 3,
                'preorder_limit' => null,
                'category' => 'Drum Set',
                'brand' => 'Yamaha',
                'shipping_class' => 'heavy',
                'shipping_unit' => 11,
            ],
            [
                'name' => 'JBL SRX712M Speaker',
                'short_description' => '12-inch two-way stage monitor and utility speaker.',
                'description' => 'JBL SRX712M is a professional, passive, 12-inch, two-way stage monitor, lightweight and compact, with versatile mounting options and 800W continuous power handling.',
                'regular_price' => 250000,
                'stock_status' => 'instock',
                'featured' => 1,
                'quantity' => 8,
                'preorder_limit' => null,
                'category' => 'Speakers',
                'brand' => 'H-D Hydinamics',
                'shipping_class' => 'medium',
                'shipping_unit' => 6,
            ],
            [
                'name' => 'Yamaha alto saxophones',
                'short_description' => 'Reliable alto saxophones for all levels.',
                'description' => 'Yamaha alto saxophones are renowned for being reliable, well-made instruments suitable for all levels, from beginners (YAS-280, YAS-26) to professionals (YAS-62, Custom series), known for bright, expressive tone, accurate intonation, and ergonomic key layouts.',
                'regular_price' => 250000,
                'stock_status' => 'instock',
                'featured' => 1,
                'quantity' => 4,
                'preorder_limit' => null,
                'category' => 'Saxophone',
                'brand' => 'Yamaha',
                'shipping_class' => 'medium',
                'shipping_unit' => 5,
            ],
            [
                'name' => 'Alipu public address system',
                'short_description' => 'Portable PA system with wireless mics and Bluetooth.',
                'description' => 'Alipu public address system built with 15" big magnet and big cone, 8000w, Bluetooth, USB, 2 wireless microphones 🎤, aux input, 12 volts rechargeable battery, remote control, trolley handling and other features.',
                'regular_price' => 120000,
                'stock_status' => 'instock',
                'featured' => 0,
                'quantity' => 2,
                'preorder_limit' => null,
                'category' => 'Public Address System',
                'brand' => 'H-D Hydinamics',
                'shipping_class' => 'medium',
                'shipping_unit' => 5,
            ],
            [
                'name' => 'Par Can light',
                'short_description' => 'Versatile stage lighting fixture.',
                'description' => 'Par Can light is a versatile, powerful stage lighting fixture with a strong, focused beam. Modern LED versions allow RGB/DMX control.',
                'regular_price' => 65000,
                'stock_status' => 'instock',
                'featured' => 0,
                'quantity' => 6,
                'preorder_limit' => null,
                'category' => 'Studio Equipment',
                'brand' => 'Behringer',
                'shipping_class' => 'light',
                'shipping_unit' => 2,
            ],
            [
                'name' => 'Yamaha 12ch console mixer',
                'short_description' => '12-channel mixer with USB & Bluetooth.',
                'description' => 'Yamaha 12ch console mixer with equaliser, USB, Bluetooth, 2group-2aux, pfl, phantom and other features.',
                'regular_price' => 180000,
                'stock_status' => 'instock',
                'featured' => 0,
                'quantity' => 3,
                'preorder_limit' => null,
                'category' => 'Mixers',
                'brand' => 'Yamaha',
                'shipping_class' => 'medium',
                'shipping_unit' => 5,
            ],
            [
                'name' => 'Focusrite scarlet 2i2 soundcard',
                'short_description' => 'Compact USB audio interface with mic and headphones.',
                'description' => 'Focusrite scarlet 2i2 soundcard complete, compact home recording solution featuring 2-in/2-out USB interface, CM25 MkIII condenser mic, HP60 MkIII headphones, software bundle.',
                'regular_price' => 450000,
                'stock_status' => 'instock',
                'featured' => 1,
                'quantity' => 4,
                'preorder_limit' => null,
                'category' => 'Studio Equipment',
                'brand' => 'Focusrite',
                'shipping_class' => 'medium',
                'shipping_unit' => 5,
            ],
            [
                'name' => 'Rechargeable shure dual wireless microphone',
                'short_description' => 'Dual wireless microphone system.',
                'description' => 'Rechargeable shure dual wireless microphone.',
                'regular_price' => 50000,
                'stock_status' => 'instock',
                'featured' => 0,
                'quantity' => 5,
                'preorder_limit' => null,
                'category' => 'Microphones',
                'brand' => 'Shure',
                'shipping_class' => 'light',
                'shipping_unit' => 1,
            ],
            [
                'name' => 'DBX 234XL professional-grade dual-channel electronic crossover',
                'short_description' => 'Professional 2-channel electronic crossover.',
                'description' => 'DBX 234XL splits audio signals into frequency bands for PA systems, flexible 2-way/3-way/4-way, XLR outputs, phase invert, and 40Hz low-cut filter.',
                'regular_price' => 50000,
                'stock_status' => 'instock',
                'featured' => 0,
                'quantity' => 4,
                'preorder_limit' => null,
                'category' => 'Studio Equipment',
                'brand' => 'DBX',
                'shipping_class' => 'light',
                'shipping_unit' => 2,
            ],
            [
                'name' => 'Portable megaphone',
                'short_description' => 'Portable megaphone for announcements.',
                'description' => 'Portable megaphone for announcement, adverts, and information dissemination.',
                'regular_price' => 15000,
                'stock_status' => 'instock',
                'featured' => 0,
                'quantity' => 10,
                'preorder_limit' => null,
                'category' => 'Public Address System',
                'brand' => 'Sound Prince',
                'shipping_class' => 'light',
                'shipping_unit' => 1,
            ],
            [
                'name' => 'Quality HD HYDINAMIC 4CHANELS POWER AMPLIFIER',
                'short_description' => '4-channel HD Power Amplifier.',
                'description' => 'Quality HD HYDINAMIC 4CHANELS POWER AMPLIFIER',
                'regular_price' => 450000,
                'stock_status' => 'instock',
                'featured' => 1,
                'quantity' => 3,
                'preorder_limit' => null,
                'category' => 'Power Amplifier',
                'brand' => 'H-D Hydinamics',
                'shipping_class' => 'extra_heavy',
                'shipping_unit' => 25,
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            $brand = Brand::where('name', $productData['brand'])->first();
            $shippingClass = ShippingClass::where('name', $productData['shipping_class'])->first();

            if (!$category || !$brand || !$shippingClass) continue;

            $sku = SkuGenerator::generate($category->code);

            DB::table('products')->insert([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'short_description' => $productData['short_description'],
                'description' => $productData['description'],
                'regular_price' => $productData['regular_price'],
                'sale_price' => $productData['sale_price'] ?? null,
                'SKU' => $sku,
                'stock_status' => $productData['stock_status'],
                'featured' => $productData['featured'],
                'quantity' => $productData['quantity'],
                'preorder_limit' => $productData['preorder_limit'],
                'image' => null,
                'images' => null,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'shipping_class_id' => $shippingClass->id,
                'shipping_unit' => $productData['shipping_unit'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
