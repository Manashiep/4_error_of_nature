<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('terra_requests', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('requester_email')->nullable();
            $table->string('service')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('terra_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['requester_email', 'service']);
        });
    }
};
