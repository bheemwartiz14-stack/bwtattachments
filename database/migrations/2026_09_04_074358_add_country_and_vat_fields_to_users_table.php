<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Idempotent columns (a previous failed run may have left vat_id behind).
        if (! Schema::hasColumn('users', 'vat_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->uuid('vat_id')->nullable()->after('id');
            });
        }

        if (! Schema::hasColumn('users', 'country')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('country')->nullable()->after('vat_id');
            });
        }

        if (! Schema::hasColumn('users', 'country_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('country_code', 3)->nullable()->after('country');
            });
        }

        // A foreign key requires identical type + charset + collation on both
        // sides. Align vat_id with vat_rates.id before constraining.
        $collation = DB::table('information_schema.columns')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'vat_rates')
            ->where('column_name', 'id')
            ->value('collation_name');

        if (is_string($collation) && $collation !== '') {
            $collation = str_replace('`', '', $collation);
            DB::statement("ALTER TABLE `users` MODIFY `vat_id` CHAR(36) NULL COLLATE `{$collation}`");
        }

        if (! $this->foreignKeyExists('users', 'users_vat_id_foreign')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('vat_id')
                    ->references('id')
                    ->on('vat_rates')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->foreignKeyExists('users', 'users_vat_id_foreign')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['vat_id']);
            });
        }

        foreach (['vat_id', 'country', 'country_code'] as $column) {
            if (Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    private function foreignKeyExists(string $table, string $constraint): bool
    {
        return DB::table('information_schema.key_column_usage')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('constraint_name', $constraint)
            ->exists();
    }
};
