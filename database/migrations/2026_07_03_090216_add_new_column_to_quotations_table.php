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
        Schema::table('quotations', function (Blueprint $table) {
            $table->date('valid_until')
                ->nullable()
                ->after('quotation_number');

            $table->date('issue_date')
                ->nullable()
                ->after('valid_until');

            $table->text('notes')
                ->nullable()
                ->after('issue_date');

            $table->string('sub_total')
                ->default(0.00)
                ->after('notes');

            $table->string('tax_amount')
                ->default(0.00)
                ->after('sub_total');

            $table->string('margin_amount')
                ->default(0.00)
                ->after('tax_amount');

            $table->string('margin_percentage', 5, 2)
                ->default(0.00)
                ->after('margin_amount');

            $table->string('vat_percentage', 5, 2)
                ->default(0.00)
                ->after('margin_percentage');

            $table->string('delivery_country')
                ->nullable()
                ->after('vat_percentage');

            $table->string('grand_total')
                ->default(0.00)
                ->after('delivery_country');

            $table->text('customer_terms')
                ->nullable()
                ->after('grand_total');

            $table->string('reference')
                ->nullable()
                ->after('grand_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'valid_until',
                'issue_date',
                'notes',
                'sub_total',
                'tax_amount',
                'margin_amount',
                'margin_percentage',
                'vat_percentage',
                'delivery_country',
                'grand_total',
                'reference',
                'customer_terms'
            ]);
        });
    }
};
