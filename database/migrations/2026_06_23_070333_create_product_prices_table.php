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
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->default('wholesale_purchase');
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('margin', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
