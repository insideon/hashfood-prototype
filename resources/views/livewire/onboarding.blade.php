<div>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 dark:from-zinc-900 dark:to-zinc-800 flex items-center justify-center p-4">
        <div class="max-w-2xl w-full">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">단계 {{ $currentStep }} / {{ $totalSteps }}</flux:text>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">{{ number_format(($currentStep / $totalSteps) * 100) }}%</flux:text>
                </div>
                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                    <div class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full transition-all duration-300"
                         style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        @if($currentStep === 1)
                            <flux:icon.heart class="w-8 h-8 text-white" />
                        @elseif($currentStep === 2)
                            <flux:icon.currency-dollar class="w-8 h-8 text-white" />
                        @else
                            <flux:icon.shield-check class="w-8 h-8 text-white" />
                        @endif
                    </div>
                    <flux:heading size="2xl" class="text-zinc-900 dark:text-zinc-100 mb-2">
                        {{ $this->stepTitle }}
                    </flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        {{ $this->stepDescription }}
                    </flux:text>
                </div>

                <!-- Step Content -->
                @if($currentStep === 1)
                    <!-- Step 1: Recipe Selection -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($availableRecipes as $recipe)
                                <div class="relative">
                                    <button
                                        wire:click="toggleRecipe({{ $recipe->id }})"
                                        class="w-full p-4 border-2 rounded-lg transition-all duration-200 {{ in_array($recipe->id, $selectedRecipes) ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}"
                                    >
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-rose-500 rounded-lg flex items-center justify-center">
                                                <flux:icon.fire class="w-6 h-6 text-white" />
                                            </div>
                                            <div class="flex-1 text-left">
                                                <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">
                                                    {{ $recipe->name }}
                                                </flux:text>
                                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                                    {{ $recipe->cooking_time }}분 • {{ $recipe->difficulty }}급
                                                </flux:text>
                                            </div>
                                            @if(in_array($recipe->id, $selectedRecipes))
                                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                    <flux:icon.check class="w-4 h-4 text-white" />
                                                </div>
                                            @endif
                                        </div>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center">
                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ count($selectedRecipes) }}/5 선택됨 (최소 3개 선택 필요)
                            </flux:text>
                        </div>
                    </div>

                @elseif($currentStep === 2)
                    <!-- Step 2: Budget Setting -->
                    <div class="space-y-6">
                        <div>
                            <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">월 예산 설정</flux:text>
                            <div class="relative">
                                <flux:input
                                    wire:model.live="budgetLimit"
                                    type="number"
                                    placeholder="예산을 입력하세요"
                                    class="text-lg"
                                />
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <flux:text class="text-zinc-500">원</flux:text>
                                </div>
                            </div>
                        </div>

                        <div>
                            <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">식자재 품질 선호도</flux:text>
                            <div class="grid grid-cols-2 gap-4">
                                <button
                                    wire:click="$set('preferredQuality', 'normal')"
                                    class="p-4 border-2 rounded-lg transition-all duration-200 {{ $preferredQuality === 'normal' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}"
                                >
                                    <div class="text-center">
                                        <flux:icon.shopping-cart class="w-8 h-8 text-zinc-600 dark:text-zinc-400 mx-auto mb-2" />
                                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">일반</flux:text>
                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">표준 품질</flux:text>
                                    </div>
                                </button>

                                <button
                                    wire:click="$set('preferredQuality', 'premium')"
                                    class="p-4 border-2 rounded-lg transition-all duration-200 {{ $preferredQuality === 'premium' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}"
                                >
                                    <div class="text-center">
                                        <flux:icon.sparkles class="w-8 h-8 text-zinc-600 dark:text-zinc-400 mx-auto mb-2" />
                                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">프리미엄</flux:text>
                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">고급 품질</flux:text>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Budget Preview -->
                        <div class="bg-zinc-50 dark:bg-zinc-700 rounded-lg p-4">
                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">예산 미리보기</flux:text>
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <flux:text class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                                        ₩{{ number_format($budgetLimit / 4) }}
                                    </flux:text>
                                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">주간 예산</flux:text>
                                </div>
                                <div>
                                    <flux:text class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                                        ₩{{ number_format($budgetLimit / 30) }}
                                    </flux:text>
                                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">일일 예산</flux:text>
                                </div>
                                <div>
                                    <flux:text class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                                        ₩{{ number_format($budgetLimit / 90) }}
                                    </flux:text>
                                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">끼니당 예산</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($currentStep === 3)
                    <!-- Step 3: Dietary Restrictions -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($availableRestrictions as $key => $label)
                                <button
                                    wire:click="toggleRestriction('{{ $key }}')"
                                    class="p-3 border-2 rounded-lg transition-all duration-200 {{ in_array($key, $dietaryRestrictions) ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}"
                                >
                                    <div class="flex items-center justify-between">
                                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $label }}
                                        </flux:text>
                                        @if(in_array($key, $dietaryRestrictions))
                                            <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                                <flux:icon.check class="w-3 h-3 text-white" />
                                            </div>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        <div class="text-center">
                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ count($dietaryRestrictions) }}개 선택됨 (선택사항)
                            </flux:text>
                        </div>
                    </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8">
                    @if($currentStep > 1)
                        <flux:button wire:click="previousStep" variant="outline">
                            <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                            이전
                        </flux:button>
                    @else
                        <div></div>
                    @endif

                    @if($currentStep < $totalSteps)
                        <flux:button
                            wire:click="nextStep"
                            :disabled="!$this->canProceed"
                            color="blue"
                        >
                            다음
                            <flux:icon.arrow-right class="w-4 h-4 ml-2" />
                        </flux:button>
                    @else
                        <flux:button
                            wire:click="completeOnboarding"
                            color="green"
                        >
                            완료하기
                            <flux:icon.check class="w-4 h-4 ml-2" />
                        </flux:button>
                    @endif
                </div>
            </div>

            <!-- Skip Option -->
            <div class="text-center mt-6">
                <flux:button wire:click="completeOnboarding" variant="ghost" class="text-zinc-500 dark:text-zinc-400">
                    나중에 설정하기
                </flux:button>
            </div>
        </div>
    </div>
</div>