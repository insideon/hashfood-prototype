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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('cooking_time')->comment('minutes');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('servings')->default(2);
            $table->string('category')->index();
            $table->string('image_url')->nullable();
            $table->decimal('delivery_price', 10, 2)->nullable()->comment('average delivery price');
            $table->text('instructions')->nullable();
            $table->timestamps();

            $table->index(['category', 'difficulty']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
