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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Ex: Ligne A - Centre / Port
            $table->string('code')->unique();                // Ex: L-01
            $table->string('type')->default('Bus');          // Bus, Navette, Tram...
            $table->text('route_description')->nullable();   // Trajet et arrêts principaux
            $table->string('frequency')->nullable();         // Ex: Toutes les 15 minutes

            // Le champ clé pour les horaires demandés par la F36
            $table->text('schedules')->nullable();           // Ex: Départs de 6h00 à 21h00 (Toutes les 15 min en heure de pointe...)

            $table->enum('status', ['Normal', 'Perturbé', 'Interrompu'])->default('Normal');
            $table->text('status_message')->nullable();      // Détails en cas de perturbation
            $table->boolean('is_active')->default(true);     // Actif ou non
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
