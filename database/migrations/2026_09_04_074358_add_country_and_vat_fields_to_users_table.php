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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('vat_id')
                ->nullable()
                ->after('id')
                ->constrained('vat_rates')
                ->nullOnDelete();

            $table->string('country')
                ->nullable()
                ->after('vat_id');

            $table->string('country_code', 3)
                ->nullable()
                ->after('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['vat_id']);

            $table->dropColumn([
                'vat_id',
                'country',
                'country_code',
            ]);
        });
    }
};
