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
            $table->string('libelleClasse')->default('6eme')->change();
            $table->integer('effectifClasse')->default(50)->change();
            $table->enum('cycleClasse', ['2nd Cycle','1er Cycle'])->nullable(false)->change();
        });
        Schema::table('matieres', function (Blueprint $table) {
            $table->string('libelleMatiere')->default('Informatique')->change();
            $table->string('codeMatiere')->defautl('INFO')->change();
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
