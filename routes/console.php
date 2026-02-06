<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SwapProductPrices;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('swap-product-prices', function () {
    $this->call('app:swap-product-prices');
})->purpose('Swap the values between regular_price and sale_price columns in the products table');