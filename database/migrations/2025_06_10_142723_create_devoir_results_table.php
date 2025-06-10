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
        Schema::create('devoir_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devoir_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('score');
            $table->integer('total_questions');
            $table->float('percentage');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devoir_results');
    }
};
