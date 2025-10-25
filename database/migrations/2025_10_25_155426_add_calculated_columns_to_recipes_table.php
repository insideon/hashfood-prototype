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
        Schema::table('recipes', function (Blueprint $table) {
            $table->decimal('cooking_cost', 10, 2)->nullable()->after('delivery_price')->index();
            $table->decimal('savings', 10, 2)->nullable()->after('cooking_cost')->index();
            $table->decimal('savings_percentage', 5, 2)->nullable()->after('savings')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn(['cooking_cost', 'savings', 'savings_percentage']);
        });
    }
};
