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
       Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone');
            $table->foreignId('state_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('lagos_location_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('lga');
            $table->string('street');
            $table->string('landmark')->nullable();
            $table->string('description');
            $table->text('additional_info')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
