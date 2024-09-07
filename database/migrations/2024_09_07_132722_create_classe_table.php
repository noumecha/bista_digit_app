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
        Schema::create('classe', function (Blueprint $table) {
            $table->id();
            $table->string('libelleClasse');
            $table->integer('effectifClasse');
            $table->enum('cycleClasse', ['2nd Cycle','1er Cycle'])->nullable();
            $table->enum('serieClasse', ['C','D','A','TI','B'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classe');
    }
};
