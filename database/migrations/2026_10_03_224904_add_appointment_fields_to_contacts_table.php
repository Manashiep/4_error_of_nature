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
        Schema::table('contacts', function (Blueprint $table) {
            Schema::table('contacts', function (Blueprint $table) {
            // Créneaux horaires
            $table->dateTime('requested_at')->nullable()->after('message'); // Créneau souhaité par l'habitant
            $table->dateTime('confirmed_at')->nullable()->after('requested_at'); // Horaire accordé par l'agent

            // Raison du refus ou consignes
            $table->text('rejection_reason')->nullable()->after('confirmed_at');
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn([
                'requested_at',
                'confirmed_at',
                'rejection_reason',
            ]);
        });
    }
};
