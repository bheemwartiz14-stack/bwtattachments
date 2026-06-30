<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop FK safely (if exists)
        $database = DB::getDatabaseName();

        $fks = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = 'product_prices'
              AND COLUMN_NAME = 'assigned_by'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$database]);

        foreach ($fks as $fk) {
            DB::statement("ALTER TABLE product_prices DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        Schema::table('product_prices', function (Blueprint $table) {

            if (Schema::hasColumn('product_prices', 'assigned_by')) {
                $table->dropColumn('assigned_by');
            }

            if (Schema::hasColumn('product_prices', 'price')) {
                $table->dropColumn('price');
            }

            if (!Schema::hasColumn('product_prices', 'base_price')) {
                $table->decimal('base_price', 10, 2)->after('type');
            }

            if (!Schema::hasColumn('product_prices', 'final_price')) {
                $table->decimal('final_price', 10, 2)->after('base_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_prices', function (Blueprint $table) {

            if (Schema::hasColumn('product_prices', 'base_price')) {
                $table->dropColumn('base_price');
            }

            if (Schema::hasColumn('product_prices', 'final_price')) {
                $table->dropColumn('final_price');
            }

            if (!Schema::hasColumn('product_prices', 'price')) {
                $table->decimal('price', 10, 2)->after('type');
            }

            if (!Schema::hasColumn('product_prices', 'assigned_by')) {
                $table->uuid('assigned_by')->nullable();
            }
        });

        // Recreate FK safely
        DB::statement("
            ALTER TABLE product_prices
            ADD CONSTRAINT product_prices_assigned_by_foreign
            FOREIGN KEY (assigned_by)
            REFERENCES users(id)
            ON DELETE SET NULL
        ");
    }
};
