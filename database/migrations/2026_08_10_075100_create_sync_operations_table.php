<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tracks processed client operations for idempotency (dedupe retries).
        Schema::create('sync_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('operation_uuid', 64)->index();
            $table->string('entity', 50);
            $table->string('operation', 20);
            $table->string('server_record_id', 64)->nullable();
            $table->unsignedInteger('status')->default(0); // 0=pending,1=applied,2=conflict
            $table->timestamps();

            $table->unique(['user_id', 'operation_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_operations');
    }
};
