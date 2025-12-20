<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Eusonlito\LaravelMeta\Facade as Meta;

class WorkshopController extends Controller
{
    public function index()
    {
        $title = env('APP_NAME').' - Workshops';
        $route = route('site.workshops');
        $description = 'Confira nossos workshops.';

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

        $workshops = Workshop::public()
            ->published()
            ->orderBy('scheduled_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();

        return view('site.workshops.index', compact('workshops'));
    }

    public function show($slug)
    {
        $workshop = Workshop::where('slug', $slug)
            ->public()
            ->published()
            ->with('user')
            ->firstOrFail();

        $title = env('APP_NAME').' - '.$workshop->title;
        $route = route('site.workshops.show', ['slug' => $workshop->slug]);
        $description = $workshop->description ?? 'Workshop - '.$workshop->title;

        /** Meta */
        Meta::title($title);
        Meta::set('description', $description);
        Meta::set('og:type', 'article');
        Meta::set('og:site_name', $title);
        Meta::set('og:locale', app()->getLocale());
        Meta::set('og:url', $route);
        Meta::set('twitter:url', $route);
        Meta::set('robots', 'index,follow');
        Meta::set('image', ($workshop->cover ? asset($workshop->cover) : asset('img/share.png')));
        Meta::set('canonical', $route);

        $relatedWorkshops = Workshop::public()
            ->published()
            ->where('id', '!=', $workshop->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('site.workshops.show', compact('workshop', 'relatedWorkshops'));
    }
}
