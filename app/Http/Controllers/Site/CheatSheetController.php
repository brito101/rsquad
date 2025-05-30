<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\CheatSheetCategory;
use Eusonlito\LaravelMeta\Facade as Meta;

class CheatSheetController extends Controller
{
    public function index()
    {
        $title = env('APP_NAME') . ' - Cheat Sheet';
        $route = route('site.cheat-sheets');
        $description = 'Confira nosso Cheat Sheet.';
        /** Meta */
        Meta::title($title);
        Meta::set('description', $description);
        Meta::set('og:type', 'article');
        Meta::set('og:site_name', $title);
        Meta::set('og:locale', app()->getLocale());
        Meta::set('og:url', $route);
        Meta::set('twitter:url', $route);
        Meta::set('robots', 'index,follow');
        Meta::set('image', asset('img/share.png'));
        Meta::set('canonical', $route);

        $categories = CheatSheetCategory::orderBy('title')->get();

        return view('site.cheat-sheets.index', compact('categories'));
    }
    public function search() {}

    public function post() {}

    public function category() {}
}
