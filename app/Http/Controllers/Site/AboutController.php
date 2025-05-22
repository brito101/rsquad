<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Eusonlito\LaravelMeta\Facade as Meta;

class AboutController extends Controller
{
    public function index()
    {
        $title = env('APP_NAME') . ' - Sobre nós';
        $route = route('site.about');
        $description = 'Saiba mais sobre a ' . env('APP_NAME') . '!';
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

        return view('site.about.index');
    }
}
