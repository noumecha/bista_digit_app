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
        Schema::table('classes', function (Blueprint $table) {
            $table->string('libelleClasse')->nullable(false)->change();
            $table->integer('effectifClasse')->nullable(false)->change();
            $table->enum('cycleClasse', ['2nd Cycle','1er Cycle'])->nullable(false)->change();
        });
        Schema::table('matieres', function (Blueprint $table) {
            $table->string('libelleMatiere')->nullable(false)->change();
            $table->string('codeMatiere')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
