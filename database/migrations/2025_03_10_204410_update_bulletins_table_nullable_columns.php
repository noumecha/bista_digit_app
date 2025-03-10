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
        Schema::table('bulletins', function (Blueprint $table) {
            $table->string('bulletin_file')->nullable()->change();
            $table->double('min_average')->nullable()->change();
            $table->double('max_average')->nullable()->change();
            $table->double('general_average')->nullable()->change();
            $table->double('standard_deviation')->nullable()->change();
            $table->integer('range')->nullable()->change();
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
