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
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained()->onDelete('cascade');
            $table->string('bulletin_file')->nullable();
            $table->string('type_bulletin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
