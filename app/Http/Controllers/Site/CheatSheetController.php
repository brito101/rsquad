<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\CheatSheet;
use App\Models\CheatSheetCategory;
use Eusonlito\LaravelMeta\Facade as Meta;
use Illuminate\Support\Facades\Auth;

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

        $categories = CheatSheetCategory::orderBy('title')->with('cheats', function ($query) {
            $query->where('status', 'Postado')->orderBy('title');
        })->orderBy('title')->get();

        return view('site.cheat-sheets.index', compact('categories'));
    }
    public function post($uri)
    {
        $uri = filter_var($uri, 513);
        $post = CheatSheet::where('uri', $uri)->where('status', '!=', 'Lixo')->first();

        if ($post) {

            $title = env('APP_NAME') . ' - ' . $post->title;
            $route = route('site.blog.post', ['uri' => $uri]);
            $description = 'Cheat Sheet';

            /** Meta */
            Meta::title($title);
            Meta::set('description', $description);
            Meta::set('og:type', 'article');
            Meta::set('og:site_name', $title);
            Meta::set('og:locale', app()->getLocale());
            Meta::set('og:url', $route);
            Meta::set('twitter:url', $route);
            Meta::set('robots', 'index,follow');
            Meta::set('image', url('storage/blog/min/' . $post->cover));
            Meta::set('canonical', $route);

            if (! Auth::user()) {
                $post->views++;
                $post->update();
            }

            return view('site.cheat-sheets.post', compact('post'));
        } else {
            return view('errors.404');
        }
    }
}
