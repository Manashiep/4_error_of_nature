<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary');
            $table->text('body')->nullable();
            $table->string('category')->default('Ville');
            $table->string('level')->default('info');      // info | important | alerte
            $table->string('action')->nullable();          // "Que faire"
            $table->boolean('is_banner')->default(false);  // affiché en bandeau sur toutes les pages
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('expires_at')->nullable();   // fin d'affichage du bandeau
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
