<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class SwapProductPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:swap-product-prices {--eloquent : Use Eloquent instead of direct SQL for the swap}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Swap the values between regular_price and sale_price columns in the products table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting the price swap operation...');
        
        // Confirm with the user before proceeding
        if (!$this->confirm('This will swap all regular_price and sale_price values in the products table. Do you want to continue?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $useEloquent = $this->option('eloquent');
        
        try {
            DB::beginTransaction();
            
            // Get the count of products with prices
            $productCount = Product::whereNotNull('regular_price')
                                  ->orWhereNotNull('sale_price')
                                  ->count();
            
            $this->info("Found {$productCount} products with price data.");

            if ($useEloquent) {
                // Use Eloquent to update each product individually
                $products = Product::select(['id', 'regular_price', 'sale_price'])
                                   ->whereNotNull('regular_price')
                                   ->orWhereNotNull('sale_price')
                                   ->get();
                
                foreach ($products as $product) {
                    $newRegularPrice = $product->sale_price;
                    $newSalePrice = $product->regular_price;
                    
                    $product->update([
                        'regular_price' => $newRegularPrice,
                        'sale_price' => $newSalePrice
                    ]);
                }
            } else {
                // Add a temporary column to assist with the swap
                Schema::table('products', function ($table) {
                    $table->decimal('temp_price', 10, 2)->nullable()->after('sale_price');
                });

                // Copy regular_price to temp_price
                DB::statement('UPDATE products SET temp_price = regular_price');

                // Copy sale_price to regular_price
                DB::statement('UPDATE products SET regular_price = sale_price');

                // Copy temp_price (original regular_price) to sale_price
                DB::statement('UPDATE products SET sale_price = temp_price');

                // Remove the temporary column
                Schema::table('products', function ($table) {
                    $table->dropColumn('temp_price');
                });
            }

            DB::commit();

            // Count how many products were affected
            $affectedProducts = Product::whereNotNull('regular_price')
                                      ->orWhereNotNull('sale_price')
                                      ->count();

            $this->info("Successfully swapped prices for {$affectedProducts} products.");
            
            // Verify the operation by checking a few records
            $sampleProducts = Product::select(['id', 'name', 'regular_price', 'sale_price'])
                                    ->limit(5)
                                    ->get();
                                    
            if ($sampleProducts->count() > 0) {
                $this->info("\nSample of updated products:");
                $this->table(
                    ['ID', 'Name', 'Regular Price', 'Sale Price'],
                    $sampleProducts->map(function ($product) {
                        return [
                            $product->id,
                            str_limit($product->name, 20),
                            $product->regular_price ?? 'NULL',
                            $product->sale_price ?? 'NULL'
                        ];
                    })
                );
            } else {
                $this->info("No products found to display samples.");
            }

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('An error occurred during the swap operation: ' . $e->getMessage());
            return 1;
        }

        $this->info('Price swap operation completed successfully!');
        return 0;
    }
}