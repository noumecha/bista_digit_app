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
        Schema::create('published_statistics', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['trimestriel', 'obc']);
            $table->foreignId('trimestre_id')->nullable();
            $table->foreignId('annee_scolaire_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('classe_id')->nullable();
            $table->integer('obc_rank')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('published_statistics');
    }
};
