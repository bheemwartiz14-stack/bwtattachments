<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('product_id') ->constrained()->onDelete('cascade');
            $table->foreignId('user_id') ->constrained() ->onDelete('cascade');
            $table->decimal('price', 10, 2)->nullable(); 
            $table->timestamps();
            $table->unique(['product_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};