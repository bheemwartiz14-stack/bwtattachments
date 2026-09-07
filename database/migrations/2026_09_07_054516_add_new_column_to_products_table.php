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
        if (Schema::hasColumn('products', 'manufacture_year')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'pin_hole')) {
                $table->unsignedSmallInteger('manufacture_year')->nullable()->after('pin_hole');
            } else {
                $table->unsignedSmallInteger('manufacture_year')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'manufacture_year')) {
                $table->dropColumn('manufacture_year');
            }
        });
    }
};
