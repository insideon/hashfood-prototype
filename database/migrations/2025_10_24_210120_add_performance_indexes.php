<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 인덱스 존재 여부를 확인하는 헬퍼 메서드
     */
    private function indexExists(string $table, string $index): bool
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            $indexes = DB::select("
                SELECT indexname
                FROM pg_indexes
                WHERE tablename = ? AND indexname = ?
            ", [$table, $index]);
        } else {
            // SQLite의 경우
            $indexes = DB::select("
                SELECT name
                FROM sqlite_master
                WHERE type = 'index' AND tbl_name = ? AND name = ?
            ", [$table, $index]);
        }

        return !empty($indexes);
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // recipes 테이블에 정렬 성능 향상을 위한 인덱스 추가
        Schema::table('recipes', function (Blueprint $table) {
            // 기본 정렬용 인덱스 (존재하지 않는 경우만 추가)
            if (!$this->indexExists('recipes', 'recipes_name_index')) {
                $table->index('name');
            }
            if (!$this->indexExists('recipes', 'recipes_category_index')) {
                $table->index('category');
            }
            if (!$this->indexExists('recipes', 'recipes_difficulty_index')) {
                $table->index('difficulty');
            }
            if (!$this->indexExists('recipes', 'recipes_cooking_time_index')) {
                $table->index('cooking_time');
            }
            if (!$this->indexExists('recipes', 'recipes_delivery_price_index')) {
                $table->index('delivery_price');
            }

            // 복합 인덱스 (필터링 + 정렬)
            if (!$this->indexExists('recipes', 'recipes_category_difficulty_cooking_time_index')) {
                $table->index(['category', 'difficulty', 'cooking_time']);
            }
            if (!$this->indexExists('recipes', 'recipes_category_delivery_price_index')) {
                $table->index(['category', 'delivery_price']);
            }
        });

        // activity_logs 테이블에 대시보드 쿼리 최적화를 위한 인덱스 추가
        Schema::table('activity_logs', function (Blueprint $table) {
            // 대시보드 통계 쿼리용 복합 인덱스
            if (!$this->indexExists('activity_logs', 'activity_logs_user_id_decision_type_created_at_index')) {
                $table->index(['user_id', 'decision_type', 'created_at']);
            }
            if (!$this->indexExists('activity_logs', 'activity_logs_user_id_created_at_index')) {
                $table->index(['user_id', 'created_at']);
            }
            if (!$this->indexExists('activity_logs', 'activity_logs_decision_type_created_at_index')) {
                $table->index(['decision_type', 'created_at']);
            }

            // 절약금액 합계 쿼리용 인덱스
            if (!$this->indexExists('activity_logs', 'activity_logs_user_id_decision_type_saved_amount_index')) {
                $table->index(['user_id', 'decision_type', 'saved_amount']);
            }
        });

        // ingredients 테이블에 가격 조회 최적화를 위한 인덱스 추가
        Schema::table('ingredients', function (Blueprint $table) {
            if (!$this->indexExists('ingredients', 'ingredients_current_price_index')) {
                $table->index('current_price');
            }
            if (!$this->indexExists('ingredients', 'ingredients_category_current_price_index')) {
                $table->index(['category', 'current_price']);
            }
        });

        // recipe_ingredients 테이블에 조인 성능 향상을 위한 인덱스 추가
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            if (!$this->indexExists('recipe_ingredients', 'recipe_ingredients_recipe_id_ingredient_id_index')) {
                $table->index(['recipe_id', 'ingredient_id']);
            }
            if (!$this->indexExists('recipe_ingredients', 'recipe_ingredients_ingredient_id_recipe_id_index')) {
                $table->index(['ingredient_id', 'recipe_id']);
            }
        });

        // price_histories 테이블에 가격 히스토리 조회 최적화
        Schema::table('price_histories', function (Blueprint $table) {
            if (!$this->indexExists('price_histories', 'price_histories_ingredient_id_recorded_at_price_index')) {
                $table->index(['ingredient_id', 'recorded_at', 'price']);
            }
            if (!$this->indexExists('price_histories', 'price_histories_recorded_at_ingredient_id_index')) {
                $table->index(['recorded_at', 'ingredient_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // recipes 테이블 인덱스 제거
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['category']);
            $table->dropIndex(['difficulty']);
            $table->dropIndex(['cooking_time']);
            $table->dropIndex(['delivery_price']);
            $table->dropIndex(['category', 'difficulty', 'cooking_time']);
            $table->dropIndex(['category', 'delivery_price']);
        });

        // activity_logs 테이블 인덱스 제거
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'decision_type', 'created_at']);
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['decision_type', 'created_at']);
            $table->dropIndex(['user_id', 'decision_type', 'saved_amount']);
        });

        // ingredients 테이블 인덱스 제거
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropIndex(['current_price']);
            $table->dropIndex(['category', 'current_price']);
        });

        // recipe_ingredients 테이블 인덱스 제거
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->dropIndex(['recipe_id', 'ingredient_id']);
            $table->dropIndex(['ingredient_id', 'recipe_id']);
        });

        // price_histories 테이블 인덱스 제거
        Schema::table('price_histories', function (Blueprint $table) {
            $table->dropIndex(['ingredient_id', 'recorded_at', 'price']);
            $table->dropIndex(['recorded_at', 'ingredient_id']);
        });
    }
};