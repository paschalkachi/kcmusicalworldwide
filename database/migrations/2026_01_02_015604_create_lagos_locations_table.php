<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lagos_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., Yaba, Ikeja
            $table->decimal('shipping_price', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lagos_locations');
    }
};
