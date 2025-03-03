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
        Schema::create('app_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('school_motor');
            $table->integer('school_postal_box')->nullable();
            $table->string('school_logo')->nullable();
            $table->text('description')->nullable();
            $table->string('school_town')->nullable();
            $table->string('school_location')->nullable();
            $table->string('contact_phone_1')->nullable();
            $table->string('contact_phone_2')->nullable();
            $table->string('school_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_configurations');
    }
};
