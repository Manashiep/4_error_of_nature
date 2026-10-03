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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable(); // Résumé court pour les cartes
            $table->longText('description'); // Description détaillée
            $table->string('image_path')->nullable(); // Photo ou illustration du service
            $table->string('category')->default('Général'); // Catégorie (Technique, Administratif, Santé, etc.)
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            // Compteur dynamique d'utilisation (F28 : Plus il est cliqué, plus il remonte !)
            $table->unsignedBigInteger('views_count')->default(0);

            // Fonctionnalités requises par l'API
            $table->boolean('is_active')->default(true); // Maintenance / Indisponibilité (Req. F38)
            $table->boolean('is_featured')->default(false); // Épinglage manuel prioritaire (Req. F28)
            $table->json('translations')->nullable(); // Support multilingue (Req. D14, F27)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
