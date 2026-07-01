<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreignUuid('reseller_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->date('valid_until')->nullable();
            $table->date('issue_date')->nullable();
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reseller_id');
            $table->dropColumn(['contact_name', 'contact_email', 'contact_phone', 'valid_until', 'issue_date', 'notes']);
        });
    }
};
