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
            $table->foreignId('app_configuration_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluation_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('trimestre_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('bulletin_file');
            $table->string('appreciation');
            $table->double('average');
            $table->double('min_average');
            $table->double('max_average');
            $table->double('general_average');
            $table->double('standard_deviation');
            $table->integer('range');
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
