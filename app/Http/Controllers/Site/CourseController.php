<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Eusonlito\LaravelMeta\Facade as Meta;

class CourseController extends Controller
{
    public function index()
    {
        $title = env('APP_NAME').' - Cursos';
        $route = route('site.courses');
        $description = 'Confira nossos cursos.';
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

        $courses = Course::where('active', true)->orderBy('created_at', 'desc')
            ->with('categoriesInfo')
            ->with('instructorsInfo')
            ->get();

        return view('site.courses.index', compact('courses'));
    }

    public function show($uri)
    {
        $course = Course::where('uri', $uri)->where('active', true)->firstOrFail();

        $title = env('APP_NAME').' - '.$course->name;
        $route = route('site.courses.show', ['uri' => $course->uri]);
        $description = $course->description;
        /** Meta */
        Meta::title($title);
        Meta::set('description', $description);
        Meta::set('og:type', 'article');
        Meta::set('og:site_name', $title);
        Meta::set('og:locale', app()->getLocale());
        Meta::set('og:url', $route);
        Meta::set('twitter:url', $route);
        Meta::set('robots', 'index,follow');
        Meta::set('image', ($course->cover ? asset('storage/courses/'.$course->cover) : asset('img/share.png')));
        Meta::set('canonical', $route);

        $courses = Course::where('active', true)
            ->where('id', '!=', $course->id)
            ->inRandomOrder()->limit(3)->get();

        return view('site.courses.show', compact('course', 'courses'));
    }
}
