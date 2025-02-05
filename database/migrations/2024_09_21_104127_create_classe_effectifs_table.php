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
        Schema::create('classe_effectifs', function (Blueprint $table) {
            $table->id();
            $table->integer('effectif')->nullable();
            $table->foreignId('annee_scolaire_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('classe_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classe_effectifs');
    }
};
