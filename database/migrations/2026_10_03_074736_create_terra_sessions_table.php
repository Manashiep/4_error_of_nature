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
       Schema::create('terra_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->boolean('is_running')->nullable();
            $table->integer('current_wave')->nullable();
            $table->integer('elapsed_minutes')->nullable();
            $table->integer('visible_requests_count')->nullable();
            $table->integer('initial_requests_count')->nullable();
            $table->integer('wave_requests_count')->nullable();
            $table->integer('next_wave_number')->nullable();
            $table->integer('minutes_until_next_wave')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terra_sessions');
    }
};
