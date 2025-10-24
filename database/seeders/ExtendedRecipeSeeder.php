<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Ingredient;

class ExtendedRecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            // 한식 메인 요리 (배달비를 현실적으로 조정)
            ['name' => '비빔밥', 'category' => '밥류', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 15, 'delivery_price' => 15000],
            ['name' => '김치볶음밥', 'category' => '밥류', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 10, 'delivery_price' => 12000],
            ['name' => '잡채', 'category' => '면류', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 30, 'delivery_price' => 22000],
            ['name' => '닭볶음탕', 'category' => '찌개/탕', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 45, 'delivery_price' => 28000],
            ['name' => '삼계탕', 'category' => '찌개/탕', 'difficulty' => 'hard', 'servings' => 1, 'cooking_time' => 60, 'delivery_price' => 18000],
            ['name' => '갈비탕', 'category' => '찌개/탕', 'difficulty' => 'hard', 'servings' => 1, 'cooking_time' => 90, 'delivery_price' => 25000],
            ['name' => '육개장', 'category' => '찌개/탕', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 40, 'delivery_price' => 20000],
            ['name' => '순두부찌개', 'category' => '찌개/탕', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 20, 'delivery_price' => 15000],
            ['name' => '부대찌개', 'category' => '찌개/탕', 'difficulty' => 'easy', 'servings' => 2, 'cooking_time' => 25, 'delivery_price' => 18000],
            ['name' => '해물탕', 'category' => '찌개/탕', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 35, 'delivery_price' => 32000],

            // 볶음류
            ['name' => '닭갈비', 'category' => '볶음', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 30, 'delivery_price' => 25000],
            ['name' => '오징어볶음', 'category' => '볶음', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 25, 'delivery_price' => 22000],
            ['name' => '새우볶음', 'category' => '볶음', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 20, 'delivery_price' => 28000],
            ['name' => '고등어조림', 'category' => '조림', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 40, 'delivery_price' => 20000],
            ['name' => '갈치조림', 'category' => '조림', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 45, 'delivery_price' => 25000],
            ['name' => '감자조림', 'category' => '조림', 'difficulty' => 'easy', 'servings' => 2, 'cooking_time' => 25, 'delivery_price' => 10000],
            ['name' => '계란찜', 'category' => '찜', 'difficulty' => 'easy', 'servings' => 2, 'cooking_time' => 15, 'delivery_price' => 12000],
            ['name' => '닭가슴살샐러드', 'category' => '샐러드', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 10, 'delivery_price' => 15000],

            // 면류
            ['name' => '라면', 'category' => '면류', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 5, 'delivery_price' => 8000],
            ['name' => '비빔국수', 'category' => '면류', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 15, 'delivery_price' => 12000],
            ['name' => '냉면', 'category' => '면류', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 10, 'delivery_price' => 15000],
            ['name' => '칼국수', 'category' => '면류', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 30, 'delivery_price' => 18000],
            ['name' => '잔치국수', 'category' => '면류', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 25, 'delivery_price' => 15000],

            // 구이류
            ['name' => '삼겹살구이', 'category' => '구이', 'difficulty' => 'easy', 'servings' => 2, 'cooking_time' => 20, 'delivery_price' => 32000],
            ['name' => '갈비구이', 'category' => '구이', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 30, 'delivery_price' => 38000],
            ['name' => '닭구이', 'category' => '구이', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 40, 'delivery_price' => 22000],
            ['name' => '고등어구이', 'category' => '구이', 'difficulty' => 'easy', 'servings' => 2, 'cooking_time' => 25, 'delivery_price' => 18000],

            // 반찬류
            ['name' => '시금치나물', 'category' => '나물', 'difficulty' => 'easy', 'servings' => 2, 'cooking_time' => 10, 'delivery_price' => 8000],
            ['name' => '콩나물무침', 'category' => '나물', 'difficulty' => 'easy', 'servings' => 2, 'cooking_time' => 8, 'delivery_price' => 6000],
            ['name' => '오이무침', 'category' => '나물', 'difficulty' => 'easy', 'servings' => 2, 'cooking_time' => 10, 'delivery_price' => 8000],
            ['name' => '무생채', 'category' => '나물', 'difficulty' => 'easy', 'servings' => 2, 'cooking_time' => 12, 'delivery_price' => 6000],
            ['name' => '깍두기', 'category' => '김치', 'difficulty' => 'medium', 'servings' => 4, 'cooking_time' => 30, 'delivery_price' => 10000],
            ['name' => '배추김치', 'category' => '김치', 'difficulty' => 'hard', 'servings' => 8, 'cooking_time' => 120, 'delivery_price' => 18000],

            // 중식
            ['name' => '짜장면', 'category' => '중식', 'difficulty' => 'medium', 'servings' => 1, 'cooking_time' => 20, 'delivery_price' => 10000],
            ['name' => '짬뽕', 'category' => '중식', 'difficulty' => 'medium', 'servings' => 1, 'cooking_time' => 25, 'delivery_price' => 12000],
            ['name' => '탕수육', 'category' => '중식', 'difficulty' => 'hard', 'servings' => 2, 'cooking_time' => 40, 'delivery_price' => 32000],
            ['name' => '볶음밥', 'category' => '중식', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 15, 'delivery_price' => 10000],
            ['name' => '마파두부', 'category' => '중식', 'difficulty' => 'medium', 'servings' => 2, 'cooking_time' => 20, 'delivery_price' => 15000],
            ['name' => '깐풍기', 'category' => '중식', 'difficulty' => 'hard', 'servings' => 2, 'cooking_time' => 35, 'delivery_price' => 25000],

            // 일식
            ['name' => '초밥', 'category' => '일식', 'difficulty' => 'hard', 'servings' => 1, 'cooking_time' => 60, 'delivery_price' => 38000],
            ['name' => '라멘', 'category' => '일식', 'difficulty' => 'medium', 'servings' => 1, 'cooking_time' => 30, 'delivery_price' => 15000],
            ['name' => '우동', 'category' => '일식', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 15, 'delivery_price' => 12000],
            ['name' => '돈카츠', 'category' => '일식', 'difficulty' => 'medium', 'servings' => 1, 'cooking_time' => 25, 'delivery_price' => 18000],
            ['name' => '규동', 'category' => '일식', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 20, 'delivery_price' => 15000],

            // 양식
            ['name' => '스테이크', 'category' => '양식', 'difficulty' => 'medium', 'servings' => 1, 'cooking_time' => 30, 'delivery_price' => 45000],
            ['name' => '파스타', 'category' => '양식', 'difficulty' => 'medium', 'servings' => 1, 'cooking_time' => 25, 'delivery_price' => 22000],
            ['name' => '피자', 'category' => '양식', 'difficulty' => 'hard', 'servings' => 2, 'cooking_time' => 45, 'delivery_price' => 32000],
            ['name' => '샐러드', 'category' => '양식', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 10, 'delivery_price' => 15000],
            ['name' => '햄버거', 'category' => '양식', 'difficulty' => 'medium', 'servings' => 1, 'cooking_time' => 20, 'delivery_price' => 18000],

            // 간식/디저트
            ['name' => '팬케이크', 'category' => '디저트', 'difficulty' => 'easy', 'servings' => 2, 'cooking_time' => 20, 'delivery_price' => 12000],
            ['name' => '와플', 'category' => '디저트', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 15, 'delivery_price' => 10000],
            ['name' => '크림파스타', 'category' => '양식', 'difficulty' => 'medium', 'servings' => 1, 'cooking_time' => 25, 'delivery_price' => 20000],
            ['name' => '토스트', 'category' => '간식', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 5, 'delivery_price' => 5000],
            ['name' => '샌드위치', 'category' => '간식', 'difficulty' => 'easy', 'servings' => 1, 'cooking_time' => 10, 'delivery_price' => 10000],
        ];

        // 더 많은 레시피를 생성하기 위해 반복
        $extendedRecipes = [];
        for ($i = 0; $i < 1000; $i++) {
            $baseRecipe = $recipes[array_rand($recipes)];
            $extendedRecipes[] = [
                'name' => $baseRecipe['name'] . ' ' . ($i + 1),
                'description' => $baseRecipe['name'] . '의 맛있는 레시피입니다.',
                'category' => $baseRecipe['category'],
                'difficulty' => $baseRecipe['difficulty'],
                'servings' => $baseRecipe['servings'],
                'cooking_time' => $baseRecipe['cooking_time'] + rand(-5, 10),
                'delivery_price' => $baseRecipe['delivery_price'] + rand(-2000, 5000),
                'instructions' => '1. 재료를 준비해주세요\n2. 요리해주세요\n3. 완성!',
            ];
        }

        foreach ($extendedRecipes as $recipeData) {
            $recipe = Recipe::create($recipeData);

            // 랜덤하게 3-8개의 재료를 선택해서 연결
            $ingredients = Ingredient::inRandomOrder()->take(rand(3, 8))->get();

            foreach ($ingredients as $ingredient) {
                // 재료별로 현실적인 수량 설정
                $quantity = match($ingredient->category) {
                    '채소' => rand(100, 300), // 채소는 100-300g
                    '육류' => rand(150, 400), // 육류는 150-400g
                    '해산물' => rand(200, 500), // 해산물은 200-500g
                    '곡류' => rand(200, 400), // 곡류는 200-400g
                    '양념' => rand(10, 50), // 양념은 10-50ml/g
                    '유제품' => rand(50, 200), // 유제품은 50-200ml/g
                    '기타' => rand(50, 200), // 기타는 50-200g
                    default => rand(50, 300),
                };

                $recipe->ingredients()->attach($ingredient->id, [
                    'quantity' => $quantity,
                    'is_optional' => rand(0, 1) == 1,
                ]);
            }
        }
    }
}