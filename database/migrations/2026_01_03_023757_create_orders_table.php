<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('state_id')->constrained()->restrictOnDelete();
            $table->foreignId('lagos_location_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_price', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->foreignId('shipping_class_id')->nullable();
            $table->unsignedInteger('total_units')->default(0);

            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
