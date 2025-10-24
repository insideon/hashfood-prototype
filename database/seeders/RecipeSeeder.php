<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            [
                'name' => '김치찌개',
                'description' => '한국인의 소울푸드. 배달 김치찌개보다 집에서 끓이면 훨씬 저렴하고 맛있어요!',
                'category' => '찌개/탕',
                'difficulty' => 'easy',
                'servings' => 2,
                'cooking_time' => 25,
                'delivery_price' => 16000,
                'instructions' => '1. 김치를 먹기 좋은 크기로 썰어주세요\n2. 냄비에 식용유를 두르고 김치를 볶아주세요\n3. 돼지고기를 넣고 같이 볶아주세요\n4. 물을 붓고 두부, 양파, 대파를 넣어주세요\n5. 고춧가루, 간장, 마늘을 넣고 끓여주세요\n6. 약 15분간 끓인 후 완성!',
                'ingredients' => [
                    ['name' => '김치', 'quantity' => 300],
                    ['name' => '돼지고기(목살)', 'quantity' => 150],
                    ['name' => '두부', 'quantity' => 200],
                    ['name' => '양파', 'quantity' => 100],
                    ['name' => '대파', 'quantity' => 30],
                    ['name' => '마늘', 'quantity' => 10],
                    ['name' => '고춧가루', 'quantity' => 5],
                    ['name' => '간장', 'quantity' => 15],
                    ['name' => '식용유', 'quantity' => 10],
                ],
            ],
            [
                'name' => '된장찌개',
                'description' => '구수한 한식 대표 찌개. 야채 듬뿍 넣어 건강하게!',
                'category' => '찌개/탕',
                'difficulty' => 'easy',
                'servings' => 2,
                'cooking_time' => 20,
                'delivery_price' => 15000,
                'instructions' => '1. 냄비에 물을 붓고 멸치와 다시마로 육수를 내주세요\n2. 된장을 풀어주세요\n3. 호박, 감자, 양파, 두부를 넣어주세요\n4. 마늘, 대파를 넣고 끓여주세요\n5. 야채가 익으면 완성!',
                'ingredients' => [
                    ['name' => '된장', 'quantity' => 40],
                    ['name' => '애호박', 'quantity' => 150],
                    ['name' => '감자', 'quantity' => 100],
                    ['name' => '양파', 'quantity' => 80],
                    ['name' => '두부', 'quantity' => 150],
                    ['name' => '대파', 'quantity' => 30],
                    ['name' => '마늘', 'quantity' => 10],
                    ['name' => '고추', 'quantity' => 10],
                ],
            ],
            [
                'name' => '제육볶음',
                'category' => '볶음',
                'description' => '매콤달콤한 돼지고기 볶음. 밥도둑!',
                'difficulty' => 'medium',
                'servings' => 2,
                'cooking_time' => 30,
                'delivery_price' => 18000,
                'instructions' => '1. 돼지고기를 한입 크기로 썰어주세요\n2. 양념장을 만들어주세요 (고추장, 간장, 설탕, 마늘, 생강)\n3. 고기에 양념을 버무려 30분 재워주세요\n4. 팬에 기름을 두르고 양파를 볶아주세요\n5. 재운 고기를 넣고 센 불에서 볶아주세요\n6. 대파를 넣고 마무리!',
                'ingredients' => [
                    ['name' => '돼지고기(목살)', 'quantity' => 300],
                    ['name' => '양파', 'quantity' => 150],
                    ['name' => '대파', 'quantity' => 50],
                    ['name' => '고추장', 'quantity' => 30],
                    ['name' => '고춧가루', 'quantity' => 10],
                    ['name' => '간장', 'quantity' => 20],
                    ['name' => '설탕', 'quantity' => 15],
                    ['name' => '마늘', 'quantity' => 15],
                    ['name' => '생강', 'quantity' => 5],
                    ['name' => '참기름', 'quantity' => 10],
                    ['name' => '식용유', 'quantity' => 15],
                ],
            ],
            [
                'name' => '계란말이',
                'category' => '반찬',
                'description' => '간단하지만 맛있는 반찬. 아침 식사로 좋아요',
                'difficulty' => 'easy',
                'servings' => 2,
                'cooking_time' => 10,
                'delivery_price' => 8000,
                'instructions' => '1. 계란을 볼에 풀어주세요\n2. 소금, 후추로 간을 해주세요\n3. 팬에 식용유를 두르고 달군 후 계란물을 부어주세요\n4. 반숙이 되면 돌돌 말아주세요\n5. 한입 크기로 썰어 완성!',
                'ingredients' => [
                    ['name' => '계란', 'quantity' => 4],
                    ['name' => '대파', 'quantity' => 20],
                    ['name' => '당근', 'quantity' => 30],
                    ['name' => '소금', 'quantity' => 3],
                    ['name' => '식용유', 'quantity' => 10],
                ],
            ],
            [
                'name' => '불고기',
                'category' => '구이',
                'description' => '달콤한 양념에 재운 소고기 구이. 특별한 날에!',
                'difficulty' => 'medium',
                'servings' => 2,
                'cooking_time' => 40,
                'delivery_price' => 25000,
                'instructions' => '1. 불고기용 소고기를 준비해주세요\n2. 양념장을 만들어주세요 (간장, 설탕, 참기름, 마늘, 후추)\n3. 양파와 대파를 썰어주세요\n4. 고기와 야채를 양념에 버무려 30분 재워주세요\n5. 팬을 달구고 고기를 구워주세요\n6. 야채와 함께 볶아 완성!',
                'ingredients' => [
                    ['name' => '소고기(불고기용)', 'quantity' => 300],
                    ['name' => '양파', 'quantity' => 100],
                    ['name' => '대파', 'quantity' => 50],
                    ['name' => '간장', 'quantity' => 40],
                    ['name' => '설탕', 'quantity' => 20],
                    ['name' => '참기름', 'quantity' => 15],
                    ['name' => '마늘', 'quantity' => 15],
                    ['name' => '후추', 'quantity' => 3],
                    ['name' => '참깨', 'quantity' => 5],
                ],
            ],
        ];

        foreach ($recipes as $recipeData) {
            $ingredients = $recipeData['ingredients'];
            unset($recipeData['ingredients']);

            $recipe = \App\Models\Recipe::create($recipeData);

            foreach ($ingredients as $ingredientData) {
                $ingredient = \App\Models\Ingredient::where('name', $ingredientData['name'])->first();
                if ($ingredient) {
                    $recipe->ingredients()->attach($ingredient->id, [
                        'quantity' => $ingredientData['quantity'],
                        'is_optional' => $ingredientData['is_optional'] ?? false,
                    ]);
                }
            }
        }
    }
}
