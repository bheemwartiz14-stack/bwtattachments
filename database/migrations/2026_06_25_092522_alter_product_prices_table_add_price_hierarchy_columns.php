<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('product_prices', 'assigned_by')) {
            Schema::table('product_prices', function (Blueprint $table) {
                $table->foreignUuid('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('product_prices', 'type')) {
            Schema::table('product_prices', function (Blueprint $table) {
                $table->string('type')->default('wholesale_purchase');
            });
        }

        if (!Schema::hasColumn('product_prices', 'margin')) {
            Schema::table('product_prices', function (Blueprint $table) {
                $table->decimal('margin', 5, 2)->nullable();
            });
        }

        $dbName = DB::connection()->getDatabaseName();
        $tableName = 'product_prices';

        $oldIndexExists = !empty(DB::select(
            "SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1",
            [$dbName, $tableName, 'product_prices_product_id_user_id_unique']
        ));

        if ($oldIndexExists) {
            Schema::table('product_prices', function (Blueprint $table) {
                $table->dropUnique(['product_id', 'user_id']);
            });
        }

        $newIndexExists = !empty(DB::select(
            "SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1",
            [$dbName, $tableName, 'product_prices_product_id_user_id_type_unique']
        ));

        if (!$newIndexExists) {
            Schema::table('product_prices', function (Blueprint $table) {
                $table->unique(['product_id', 'user_id', 'type']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('product_prices', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'user_id', 'type']);
            $table->unique(['product_id', 'user_id']);
        });

        Schema::table('product_prices', function (Blueprint $table) {
            $table->dropColumn(['assigned_by', 'type', 'margin']);
        });
    }
};
