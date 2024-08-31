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
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('dateNaiss')->nullable();
            $table->string('lieuNaiss')->nullable();
            $table->string('diplome1')->nullable();
            $table->string('diplome2')->nullable();
            $table->string('matricule')->nullable();
            $table->enum('statutRedoublance', ['oui','non'])->nullable();
            $table->string('typeUser')->nullable();
            $table->enum('fonction', ['SG', 'DE','Principale','DET','DEC'])->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
