<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'expires_at']); // Index for efficient cleanup queries
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};