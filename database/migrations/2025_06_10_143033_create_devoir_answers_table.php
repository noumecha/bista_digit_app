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
        Schema::create('devoir_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_id')->constrained('devoir_results');
            $table->foreignId('question_id')->constrained();
            $table->json('selected_answers'); // Stores array of selected answer IDs
            $table->boolean('is_correct');
            $table->integer('points_earned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devoir_answers');
    }
};
