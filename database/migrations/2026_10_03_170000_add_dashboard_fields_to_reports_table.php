<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table): void {
            if (! Schema::hasColumn('reports', 'service_id')) {
                $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('reports', 'title')) {
                $table->string('title')->nullable();
            }

            if (! Schema::hasColumn('reports', 'image_path')) {
                $table->string('image_path')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table): void {
            if (Schema::hasColumn('reports', 'image_path')) {
                $table->dropColumn('image_path');
            }

            if (Schema::hasColumn('reports', 'title')) {
                $table->dropColumn('title');
            }

            if (Schema::hasColumn('reports', 'service_id')) {
                $table->dropConstrainedForeignId('service_id');
            }
        });
    }
};
