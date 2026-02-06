<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            // Basic product info
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();

            // Prices
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();

            // Inventory
            $table->string('SKU')->unique();
            $table->enum('stock_status', ['instock', 'outofstock','preorder'])->default('instock');
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedInteger('preorder_limit')->nullable();
             $table->decimal('shipping_unit', 4, 2)->default(1.0);

            // Images
            $table->string('image')->nullable();         // main image
            $table->json('images')->nullable();          // additional images

            // Relationships
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('brand_id')->nullable()->index();
            $table->foreignId('shipping_class_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
