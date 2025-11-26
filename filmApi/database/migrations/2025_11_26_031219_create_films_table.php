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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('release_year', 4);
            $table->integer('length');
            $table->text('description')->nullable();
            $table->string('rating', 5)->nullable();
            $table->foreignId('language_id')->constrained();
            $table->string('special_features', 200)->nullable();
            $table->string('image', 40)->nullable();
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};