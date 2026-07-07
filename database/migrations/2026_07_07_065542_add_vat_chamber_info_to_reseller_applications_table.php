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
        Schema::table('reseller_applications', function (Blueprint $table) {
            $table->string('vat_number')->nullable()->after('website');
            $table->string('chamber_of_commerce')->nullable()->after('vat_number');
            $table->text('additional_info')->nullable()->after('chamber_of_commerce');
        });
    }

    public function down(): void
    {
        Schema::table('reseller_applications', function (Blueprint $table) {
            $table->dropColumn(['vat_number', 'chamber_of_commerce', 'additional_info']);
        });
    }
};
