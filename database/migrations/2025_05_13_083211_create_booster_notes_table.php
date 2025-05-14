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
        Schema::create('booster_notes', function (Blueprint $table) {
            $table->id();
            $table->double('note');
            $table->string('appreciation');
            $table->foreignId('evaluation_id')->constrained()->onDelete('cascade');
            $table->foreignId('booster_matiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('remplissage_id')->constrained()->onDelete('cascade');
            $table->foreignId('booster_student_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->integer('range')->nullable();
            $table->double('gcma')->nullable();
            $table->double('min_value')->nullable();
            $table->double('max_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booster_notes');
    }
};
