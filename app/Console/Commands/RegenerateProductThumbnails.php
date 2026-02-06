<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class RegenerateProductThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbnails:regenerate-products 
                            {--dry-run : Show what would happen without actually changing files}
                            {--id= : Only regenerate thumbnail for a specific product ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate product thumbnails at the new resolution';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $imageManager = new ImageManager(new Driver());

        $query = Product::whereNotNull('image');
        
        if ($this->option('id')) {
            $productId = $this->option('id');
            $query->where('id', $productId);
            $this->info("Processing product ID: {$productId}");
        } else {
            $this->info('Starting regeneration of product thumbnails...');
        }

        $products = $query->get();
        $count = $products->count();
        
        if ($count === 0) {
            $this->info('No products with images found.');
            return;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $processedCount = 0;

        foreach ($products as $product) {
            $originalImagePath = public_path('uploads/products/' . $product->image);
            
            if (!File::exists($originalImagePath)) {
                $this->warn("\nOriginal image not found for product {$product->id}: {$product->image}");
                continue;
            }

            if (!$this->option('dry-run')) {
                // Regenerate the thumbnail at the new resolution
                $img = $imageManager
                    ->read($originalImagePath)
                    ->cover(400, 400);

                $img->save($originalImagePath, quality: 90);
            }

            $processedCount++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        
        if ($this->option('dry-run')) {
            $this->info("\nDry run completed. Would have processed {$processedCount} images.");
        } else {
            $this->info("\nSuccessfully regenerated {$processedCount} product thumbnails.");
        }
    }
}