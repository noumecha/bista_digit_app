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
            $table->string('libelleClasse')->nullable()->change();
            $table->integer('effectifClasse')->nullable()->change();
            $table->enum('cycleClasse', ['2nd Cycle','1er Cycle'])->nullable()->change();
        });
        Schema::table('matieres', function (Blueprint $table) {
            $table->string('libelleMatiere')->nullable()->change();
            $table->string('codeMatiere')->nullable()->change();
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
