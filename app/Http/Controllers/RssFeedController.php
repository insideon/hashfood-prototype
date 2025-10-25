<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Response;

class RssFeedController extends Controller
{
    public function __invoke(): Response
    {
        $recipes = Recipe::latest('created_at')->limit(50)->get();

        $rss = '<?xml version="1.0" encoding="UTF-8"?>';
        $rss .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
        $rss .= '<channel>';
        $rss .= '<title>'.htmlspecialchars(config('app.name')).'</title>';
        $rss .= '<link>'.route('home').'</link>';
        $rss .= '<description>실제 식자재 원가로 비교하는 가장 합리적인 식사 선택</description>';
        $rss .= '<language>ko</language>';
        $rss .= '<lastBuildDate>'.now()->toRssString().'</lastBuildDate>';
        $rss .= '<atom:link href="'.route('rss').'" rel="self" type="application/rss+xml" />';

        foreach ($recipes as $recipe) {
            $rss .= '<item>';
            $rss .= '<title>'.htmlspecialchars($recipe->name).'</title>';
            $rss .= '<link>'.route('recipes.show', $recipe->id).'</link>';
            $rss .= '<guid isPermaLink="true">'.route('recipes.show', $recipe->id).'</guid>';
            $rss .= '<pubDate>'.$recipe->created_at->toRssString().'</pubDate>';

            $description = '';
            if ($recipe->description) {
                $description .= htmlspecialchars($recipe->description).' ';
            }
            $description .= '원가: ₩'.number_format($recipe->cost_price);
            $description .= ' | 배달 가격: ₩'.number_format($recipe->delivery_price);
            $description .= ' | 절약: ₩'.number_format($recipe->savings);
            $description .= ' ('.number_format($recipe->savings_percentage, 1).'%)';

            $rss .= '<description>'.$description.'</description>';

            if ($recipe->category) {
                $rss .= '<category>'.htmlspecialchars($recipe->category).'</category>';
            }

            $rss .= '</item>';
        }

        $rss .= '</channel>';
        $rss .= '</rss>';

        return response($rss, 200)
            ->header('Content-Type', 'application/rss+xml');
    }
}
