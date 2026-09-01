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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('order_to_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->date('order_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('order_email_message')->nullable();
            $table->string('delivery_country')->nullable();
            $table->string('sub_total')->default(0.00);
            $table->string('vat_percentage', 5, 2)->default(0.00);
            $table->string('vat_amount')->nullable();
            $table->string('grand_total')->default(0.00);
            $table->string('order_reference', 255)->nullable();
            $table->string('pdf_file')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
