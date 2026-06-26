<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('product_title');
            $table->string('product_code')->unique();
            $table->longText('product_description')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('machine_weight', 10, 2)->nullable();
            $table->foreignUuid('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('subcategory_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('connection_id')->nullable()->constrained()->nullOnDelete();
            $table->string('hinges')->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->decimal('cutting_edge_thickness', 10, 2)->nullable();
            $table->decimal('thickness', 10, 2)->nullable();
            $table->decimal('reach', 10, 2)->nullable();
            $table->string('teeth')->nullable();
            $table->string('machine_class')->nullable();
            $table->string('material')->nullable();
            $table->decimal('stick_width', 10, 2)->nullable();
            $table->decimal('pin_center', 10, 2)->nullable();
            $table->decimal('pin_hole', 10, 2)->nullable();
             $table->decimal('ddp_price', 12, 2)->nullable();
            $table->longText('internal_notes')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
