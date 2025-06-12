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
        Schema::table('devoirs', function (Blueprint $table) {
            $table->integer('duree');
            $table->renameColumn('dateDeDebut', 'date_debut');
            $table->renameColumn('dateDeFin', 'date_fin');
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
