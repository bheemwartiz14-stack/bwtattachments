<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tombstones: propagate deletions to clients during incremental pull.
        Schema::create('sync_tombstones', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('entity', 50);
            $table->string('record_id', 64);
            $table->timestamp('deleted_at')->nullable();

            $table->index(['entity', 'record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_tombstones');
    }
};
