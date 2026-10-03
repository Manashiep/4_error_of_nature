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
        Schema::create('report_supports', function (Blueprint $table) {
           $table->id();
            // Clé étrangère vers le signalement (si le signalement est supprimé, ses soutiens le sont aussi)
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();

            // Clé étrangère vers l'utilisateur/citoyen qui soutient
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->timestamps();

            // Empêche un même utilisateur de voter plusieurs fois pour le même signalement (Anti-triche)
            $table->unique(['report_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_supports');
    }
};
