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
        Schema::create('enseignement_annee_scolaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annee_scolaire_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('enseignement_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignement_annee_scolaires');
    }
};
