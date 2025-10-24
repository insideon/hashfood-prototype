<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            // 채소류
            ['name' => '배추', 'category' => '채소', 'unit' => 'g', 'current_price' => 3.5, 'description' => '김치찌개, 배추김치용'],
            ['name' => '무', 'category' => '채소', 'unit' => 'g', 'current_price' => 2.0, 'description' => '무국, 깍두기용'],
            ['name' => '양파', 'category' => '채소', 'unit' => 'g', 'current_price' => 2.5, 'description' => '거의 모든 요리'],
            ['name' => '대파', 'category' => '채소', 'unit' => 'g', 'current_price' => 8.0, 'description' => '양념, 고명용'],
            ['name' => '마늘', 'category' => '채소', 'unit' => 'g', 'current_price' => 12.0, 'description' => '필수 양념'],
            ['name' => '생강', 'category' => '채소', 'unit' => 'g', 'current_price' => 15.0, 'description' => '육류 누린내 제거'],
            ['name' => '고추', 'category' => '채소', 'unit' => 'g', 'current_price' => 20.0, 'description' => '매운맛'],
            ['name' => '애호박', 'category' => '채소', 'unit' => 'g', 'current_price' => 4.0, 'description' => '호박볶음, 찌개용'],
            ['name' => '당근', 'category' => '채소', 'unit' => 'g', 'current_price' => 3.0, 'description' => '부재료'],
            ['name' => '감자', 'category' => '채소', 'unit' => 'g', 'current_price' => 2.5, 'description' => '감자조림, 카레용'],
            ['name' => '고구마', 'category' => '채소', 'unit' => 'g', 'current_price' => 3.0, 'description' => '간식, 반찬용'],
            ['name' => '버섯', 'category' => '채소', 'unit' => 'g', 'current_price' => 10.0, 'description' => '찌개, 볶음용'],
            ['name' => '시금치', 'category' => '채소', 'unit' => 'g', 'current_price' => 6.0, 'description' => '나물, 국용'],
            ['name' => '콩나물', 'category' => '채소', 'unit' => 'g', 'current_price' => 2.0, 'description' => '국, 나물용'],

            // 육류
            ['name' => '돼지고기(삼겹살)', 'category' => '육류', 'unit' => 'g', 'current_price' => 25.0, 'description' => '삼겹살구이, 제육볶음'],
            ['name' => '돼지고기(목살)', 'category' => '육류', 'unit' => 'g', 'current_price' => 18.0, 'description' => '제육볶음, 찌개용'],
            ['name' => '소고기(불고기용)', 'category' => '육류', 'unit' => 'g', 'current_price' => 35.0, 'description' => '불고기, 국거리'],
            ['name' => '소고기(국거리)', 'category' => '육류', 'unit' => 'g', 'current_price' => 28.0, 'description' => '소고기국'],
            ['name' => '닭고기(통닭)', 'category' => '육류', 'unit' => 'g', 'current_price' => 8.0, 'description' => '삼계탕, 닭볶음탕'],
            ['name' => '닭고기(가슴살)', 'category' => '육류', 'unit' => 'g', 'current_price' => 10.0, 'description' => '샐러드, 다이어트'],

            // 해산물
            ['name' => '고등어', 'category' => '해산물', 'unit' => 'g', 'current_price' => 12.0, 'description' => '고등어조림, 구이'],
            ['name' => '갈치', 'category' => '해산물', 'unit' => 'g', 'current_price' => 18.0, 'description' => '갈치조림'],
            ['name' => '명태', 'category' => '해산물', 'unit' => 'g', 'current_price' => 15.0, 'description' => '명태찌개'],
            ['name' => '오징어', 'category' => '해산물', 'unit' => 'g', 'current_price' => 20.0, 'description' => '오징어볶음'],
            ['name' => '새우', 'category' => '해산물', 'unit' => 'g', 'current_price' => 30.0, 'description' => '새우볶음, 튀김'],
            ['name' => '조개', 'category' => '해산물', 'unit' => 'g', 'current_price' => 12.0, 'description' => '조개찜, 칼국수'],

            // 곡류
            ['name' => '쌀', 'category' => '곡류', 'unit' => 'g', 'current_price' => 3.0, 'description' => '밥'],
            ['name' => '밀가루', 'category' => '곡류', 'unit' => 'g', 'current_price' => 1.5, 'description' => '전, 부침개'],
            ['name' => '국수', 'category' => '곡류', 'unit' => 'g', 'current_price' => 5.0, 'description' => '비빔국수, 잔치국수'],
            ['name' => '당면', 'category' => '곡류', 'unit' => 'g', 'current_price' => 8.0, 'description' => '잡채'],

            // 양념
            ['name' => '간장', 'category' => '양념', 'unit' => 'ml', 'current_price' => 8.0, 'description' => '기본 양념'],
            ['name' => '된장', 'category' => '양념', 'unit' => 'g', 'current_price' => 10.0, 'description' => '된장찌개'],
            ['name' => '고추장', 'category' => '양념', 'unit' => 'g', 'current_price' => 9.0, 'description' => '비빔밥, 찌개'],
            ['name' => '고춧가루', 'category' => '양념', 'unit' => 'g', 'current_price' => 25.0, 'description' => '김치, 매운 요리'],
            ['name' => '참기름', 'category' => '양념', 'unit' => 'ml', 'current_price' => 50.0, 'description' => '나물 무침'],
            ['name' => '식용유', 'category' => '양념', 'unit' => 'ml', 'current_price' => 5.0, 'description' => '볶음, 튀김'],
            ['name' => '설탕', 'category' => '양념', 'unit' => 'g', 'current_price' => 2.0, 'description' => '단맛'],
            ['name' => '소금', 'category' => '양념', 'unit' => 'g', 'current_price' => 1.0, 'description' => '짠맛'],
            ['name' => '후추', 'category' => '양념', 'unit' => 'g', 'current_price' => 30.0, 'description' => '양념'],
            ['name' => '굴소스', 'category' => '양념', 'unit' => 'ml', 'current_price' => 15.0, 'description' => '중식 양념'],
            ['name' => '맛술', 'category' => '양념', 'unit' => 'ml', 'current_price' => 8.0, 'description' => '요리술'],

            // 유제품
            ['name' => '우유', 'category' => '유제품', 'unit' => 'ml', 'current_price' => 3.0, 'description' => '음료, 요리'],
            ['name' => '계란', 'category' => '유제품', 'unit' => '개', 'current_price' => 300.0, 'description' => '계란찜, 계란말이'],
            ['name' => '치즈', 'category' => '유제품', 'unit' => 'g', 'current_price' => 20.0, 'description' => '토핑'],
            ['name' => '버터', 'category' => '유제품', 'unit' => 'g', 'current_price' => 25.0, 'description' => '빵, 볶음'],

            // 기타
            ['name' => '두부', 'category' => '기타', 'unit' => 'g', 'current_price' => 3.0, 'description' => '두부찌개, 부침'],
            ['name' => '김치', 'category' => '기타', 'unit' => 'g', 'current_price' => 8.0, 'description' => '김치찌개, 반찬'],
            ['name' => '김', 'category' => '기타', 'unit' => 'g', 'current_price' => 100.0, 'description' => '김밥, 반찬'],
            ['name' => '참깨', 'category' => '기타', 'unit' => 'g', 'current_price' => 40.0, 'description' => '고명'],
        ];

        foreach ($ingredients as $ingredient) {
            \App\Models\Ingredient::create(array_merge($ingredient, [
                'price_updated_at' => now(),
            ]));
        }
    }
}
