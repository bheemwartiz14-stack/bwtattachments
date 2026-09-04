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
        Schema::create('vat_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('country');
            $table->string('iso_code', 2)->index();
            $table->decimal('standard_vat_rate', 5, 2)->default(0);
            $table->string('eu_status')->nullable();
            $table->string('currency', 3)->nullable();
            $table->boolean('b2b_reverse_charge')->default(false);
            $table->timestamps();
            $table->unique('iso_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vat_rates');
    }
};
