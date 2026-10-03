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
        Schema::create('create_contacts_tables', function (Blueprint $table) {
           $table->id();

            // Différenciation de la demande
            $table->enum('type', ['general', 'service'])->default('general');
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();

            // Informations de l'expéditeur
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();

            // Contenu du message
            $table->string('subject');
            $table->text('message');

            // Suivi et traitement côté administration
            $table->enum('status', ['nouveau', 'en_cours', 'traite', 'archive'])->default('nouveau');
            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_contacts_tables');
    }
};
