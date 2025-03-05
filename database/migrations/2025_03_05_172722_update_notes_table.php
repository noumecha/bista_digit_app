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
        Schema::table('notes', function (Blueprint $table) {
            $table->integer('range')->nullable();
            $table->double('gcma')->nullable();
            $table->double('min_value')->nullable();
            $table->double('max_value')->nullable();
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
