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
        Schema::create('trimestre_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('trimestre_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained()->onDelete('cascade');
            $table->decimal('eval1_note', 5, 2)->nullable();
            $table->decimal('eval2_note', 5, 2)->nullable();
            $table->decimal('trim_note', 5, 2)->nullable();
            $table->integer('rang')->nullable();
            $table->decimal('class_avg', 5, 2)->nullable();
            $table->decimal('min_note', 5, 2)->nullable();
            $table->decimal('max_note', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trimestre_notes');
    }
};
