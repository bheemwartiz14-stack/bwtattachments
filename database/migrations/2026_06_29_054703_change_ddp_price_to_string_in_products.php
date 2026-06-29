<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('weight', 255)->nullable()->change();
            $table->string('machine_weight', 255)->nullable()->change();
            $table->string('width', 255)->nullable()->change();
            $table->string('volume', 255)->nullable()->change();
            $table->string('cutting_edge_thickness', 255)->nullable()->change();
            $table->string('thickness', 255)->nullable()->change();
            $table->string('reach', 255)->nullable()->change();
            $table->string('stick_width', 255)->nullable()->change();
            $table->string('pin_center', 255)->nullable()->change();
            $table->string('pin_hole', 255)->nullable()->change();
            $table->string('ddp_price', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('weight', 10, 2)->nullable()->change();
            $table->decimal('machine_weight', 10, 2)->nullable()->change();
            $table->decimal('width', 10, 2)->nullable()->change();
            $table->decimal('volume', 10, 2)->nullable()->change();
            $table->decimal('cutting_edge_thickness', 10, 2)->nullable()->change();
            $table->decimal('thickness', 10, 2)->nullable()->change();
            $table->decimal('reach', 10, 2)->nullable()->change();
            $table->decimal('stick_width', 10, 2)->nullable()->change();
            $table->decimal('pin_center', 10, 2)->nullable()->change();
            $table->decimal('pin_hole', 10, 2)->nullable()->change();
            $table->decimal('ddp_price', 12, 2)->nullable()->change();
        });
    }
};
