<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Testimonial;
use Eusonlito\LaravelMeta\Facade as Meta;

class HomeController extends Controller
{
    public function index()
    {
        $title = env('APP_NAME');
        $route = route('site.home');
        $description = env('APP_DESC');
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

        $courses = Course::where('active', true)->orderBy('created_at', 'desc')->take(3)->with('instructorsInfo')->get();

        // Buscar depoimentos aprovados e destacados
        $testimonials = Testimonial::approved()
            ->featured()
            ->with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('site.home.index', compact('courses', 'testimonials'));
    }
}
