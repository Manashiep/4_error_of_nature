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
        Schema::table('contacts', function (Blueprint $table): void {
            if (! Schema::hasColumn('contacts', 'requested_at')) {
                $table->dateTime('requested_at')->nullable();
            }

            if (! Schema::hasColumn('contacts', 'confirmed_at')) {
                $table->dateTime('confirmed_at')->nullable();
            }

            if (! Schema::hasColumn('contacts', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $columns = array_values(array_filter(
                ['requested_at', 'confirmed_at', 'rejection_reason'],
                fn (string $column): bool => Schema::hasColumn('contacts', $column),
            ));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
