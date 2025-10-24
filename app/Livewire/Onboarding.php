<?php

namespace App\Livewire;

use App\Models\Recipe;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Onboarding extends Component
{
    public $currentStep = 1;

    public $totalSteps = 3;

    // Step 1: 선호 메뉴 선택
    public $selectedRecipes = [];

    public $availableRecipes = [];

    // Step 2: 예산 설정
    public $budgetLimit = 100000;

    public $preferredQuality = 'normal';

    // Step 3: 알레르기/제한사항
    public $dietaryRestrictions = [];

    public $availableRestrictions = [
        'vegetarian' => '채식주의',
        'vegan' => '비건',
        'gluten_free' => '글루텐 프리',
        'dairy_free' => '유제품 프리',
        'nut_free' => '견과류 프리',
        'spicy_intolerant' => '매운 음식 불가',
        'seafood_allergy' => '해산물 알레르기',
    ];

    public function mount(): void
    {
        $this->availableRecipes = Recipe::with('ingredients')->get();
    }

    public function nextStep(): void
    {
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function toggleRecipe(int $recipeId): void
    {
        if (in_array($recipeId, $this->selectedRecipes)) {
            $this->selectedRecipes = array_filter($this->selectedRecipes, fn ($id) => $id !== $recipeId);
        } else {
            if (count($this->selectedRecipes) < 5) {
                $this->selectedRecipes[] = $recipeId;
            }
        }
    }

    public function toggleRestriction(string $restriction): void
    {
        if (in_array($restriction, $this->dietaryRestrictions)) {
            $this->dietaryRestrictions = array_filter($this->dietaryRestrictions, fn ($r) => $r !== $restriction);
        } else {
            $this->dietaryRestrictions[] = $restriction;
        }
    }

    public function completeOnboarding()
    {
        $user = Auth::user();

        // 사용자 선호도 생성 또는 업데이트
        UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'favorite_recipes' => $this->selectedRecipes,
                'budget_limit' => $this->budgetLimit,
                'preferred_quality' => $this->preferredQuality,
                'dietary_restrictions' => $this->dietaryRestrictions,
            ]
        );

        // 온보딩 완료 후 대시보드로 리다이렉트
        return redirect()->route('dashboard');
    }

    public function getStepTitleProperty(): string
    {
        return match ($this->currentStep) {
            1 => '좋아하는 메뉴를 선택해주세요',
            2 => '월 예산을 설정해주세요',
            3 => '알레르기나 제한사항이 있나요?',
            default => '온보딩',
        };
    }

    public function getStepDescriptionProperty(): string
    {
        return match ($this->currentStep) {
            1 => '최대 5개까지 선택할 수 있어요. 선택한 메뉴를 바탕으로 맞춤 추천을 제공합니다.',
            2 => '월 평균 식비 예산을 설정하면 예산에 맞는 레시피를 추천해드려요.',
            3 => '선택사항입니다. 알레르기나 식단 제한사항이 있다면 선택해주세요.',
            default => '',
        };
    }

    public function getCanProceedProperty(): bool
    {
        return match ($this->currentStep) {
            1 => count($this->selectedRecipes) >= 3,
            2 => $this->budgetLimit > 0,
            3 => true, // 선택사항이므로 항상 진행 가능
            default => false,
        };
    }

    public function render()
    {
        return view('livewire.onboarding');
    }
}
