<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // servings가 0인 레시피들을 1로 수정
        DB::table('recipes')
            ->where('servings', '<=', 0)
            ->update(['servings' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 이 마이그레이션은 되돌릴 수 없습니다 (데이터 수정이므로)
    }
};
