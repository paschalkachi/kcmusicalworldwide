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
        Schema::create('shipping_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();        // light, medium, heavy, extra_heavy
            $table->integer('min_units');            // minimum units for this class
            $table->integer('max_units');            // maximum units for this class
            $table->decimal('load_factor', 4, 2);   // multiplier for base shipping price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_classes');
    }
};
