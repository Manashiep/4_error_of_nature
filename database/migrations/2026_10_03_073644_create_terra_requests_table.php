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
        Schema::create('terra_requests', function (Blueprint $table) {
            $table->id(); // Représente le champ "id" du JSON
            $table->string('request_code')->unique();
            $table->string('requester_name')->nullable();
            $table->string('requester_type')->nullable();
            $table->text('message_public');
            $table->string('difficulty')->nullable();
            $table->integer('xp_base')->nullable();
            $table->integer('xp_time_bonus')->nullable();
            $table->integer('xp_total')->nullable();
            $table->integer('is_ai_related')->nullable();
            $table->string('arrival_type')->nullable();
            $table->integer('wave_number')->nullable();
            $table->string('arrival_time')->nullable();
            $table->string('group_name')->nullable();
            $table->integer('sort_order')->nullable();
            $table->boolean('is_initial')->nullable();
            $table->boolean('is_ai_request')->nullable();
            $table->integer('difficulty_level')->nullable();
            $table->integer('xp_available')->nullable();
            $table->integer('visible_since_wave')->nullable();

            // Le seul champ à nous (hors API) pour le suivi d'avancement de l'équipe
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terra_requests');
    }
};
