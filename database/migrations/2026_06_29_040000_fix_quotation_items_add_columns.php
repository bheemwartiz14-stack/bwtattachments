<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->foreignUuid('quotation_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_id')->after('quotation_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 12, 2)->after('product_id');
            $table->integer('quantity')->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('quotation_id');
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn(['price', 'quantity']);
        });
    }
};
