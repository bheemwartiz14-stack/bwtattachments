<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // client_uuid lets offline-created quotations map back idempotently to a server record.
        Schema::table('quotations', function (Blueprint $table) {
            $table->uuid('client_uuid')->nullable()->after('id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('client_uuid');
        });
    }
};
