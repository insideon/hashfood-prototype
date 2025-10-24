<?php

namespace App\Http\Middleware;

use App\Models\UserPreference;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 인증된 사용자이고 온보딩이 완료되지 않은 경우
        if ($user && ! $this->hasCompletedOnboarding($user)) {
            // 온보딩 페이지가 아닌 경우에만 리다이렉트
            if (! $request->routeIs('onboarding')) {
                return redirect()->route('onboarding');
            }
        }

        return $next($request);
    }

    /**
     * 사용자가 온보딩을 완료했는지 확인합니다.
     */
    private function hasCompletedOnboarding($user): bool
    {
        $userPreference = UserPreference::where('user_id', $user->id)->first();

        // 사용자 선호도가 없거나 필수 정보가 없는 경우 온보딩 미완료
        if (! $userPreference) {
            return false;
        }

        // 최소 3개의 즐겨찾기 레시피와 예산이 설정되어 있어야 함
        $favoriteRecipes = $userPreference->favorite_recipes ?? [];
        $budgetLimit = $userPreference->budget_limit ?? 0;

        return count($favoriteRecipes) >= 3 && $budgetLimit > 0;
    }
}
