<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $recipes = Recipe::latest('updated_at')->get();

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // 홈페이지
        $sitemap .= '<url>';
        $sitemap .= '<loc>'.route('home').'</loc>';
        $sitemap .= '<lastmod>'.now()->toAtomString().'</lastmod>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>1.0</priority>';
        $sitemap .= '</url>';

        // 레시피 인덱스
        $sitemap .= '<url>';
        $sitemap .= '<loc>'.route('recipes.index').'</loc>';
        $sitemap .= '<lastmod>'.now()->toAtomString().'</lastmod>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>0.9</priority>';
        $sitemap .= '</url>';

        // 가격 트래킹
        $sitemap .= '<url>';
        $sitemap .= '<loc>'.route('price-tracking').'</loc>';
        $sitemap .= '<lastmod>'.now()->toAtomString().'</lastmod>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>0.8</priority>';
        $sitemap .= '</url>';

        // 개별 레시피
        foreach ($recipes as $recipe) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>'.route('recipes.show', $recipe->id).'</loc>';
            $sitemap .= '<lastmod>'.$recipe->updated_at->toAtomString().'</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.7</priority>';
            $sitemap .= '</url>';
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }
}
