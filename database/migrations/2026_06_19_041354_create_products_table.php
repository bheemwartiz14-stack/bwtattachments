<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('connection_id')->nullable()->constrained()->nullOnDelete();

            $table->string('product_code')->unique();
            $table->string('product_description');

            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('machine_weight', 10, 2)->nullable();
            $table->string('hinges')->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->decimal('cutting_edge_thickness', 10, 2)->nullable();
            $table->string('teeth')->nullable();
            $table->decimal('stick_width', 10, 2)->nullable();
            $table->decimal('pin_center', 10, 2)->nullable();
            $table->decimal('pin_hole', 10, 2)->nullable();

            $table->decimal('ddp_price', 12, 2)->nullable();

            $table->string('pdf_file')->nullable();

            $table->text('internal_notes')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
