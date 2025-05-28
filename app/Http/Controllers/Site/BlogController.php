<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategoriesPivot;
use App\Models\BlogCategory;
use Eusonlito\LaravelMeta\Facade as Meta;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $title = env('APP_NAME').' - Blog';
        $route = route('site.blog');
        $description = 'Confira nosso Blog.';
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

        $posts = Blog::where('status', 'Postado')->orderBy('created_at', 'desc')
            ->with('categories')
            ->paginate(9);

        return view('site.blog.index', compact('posts'));
    }

    public function post() {}

    public function category(Request $request)
    {
        $category = filter_var($request->category, 513);

        $category = BlogCategory::where('uri', $category)->first();

        if ($category) {

            $title = env('APP_NAME').' - Blog em: '.$category->title;
            $route = route('site.blog.category', ['category' => $category->uri]);
            $description = 'Confira nosso Blog em: '.$category->title;
            /** Meta */
            Meta::title($title);
            Meta::set('description', $description);
            Meta::set('og:type', 'article');
            Meta::set('og:site_name', $title);
            Meta::set('og:locale', app()->getLocale());
            Meta::set('og:url', $route);
            Meta::set('twitter:url', $route);
            Meta::set('robots', 'index,follow');
            Meta::set('image', url('storage/blog-categories/min/'.$category->cover));
            Meta::set('canonical', $route);

            $blogCategories = BlogCategoriesPivot::where('blog_category_id', $category->id)->pluck('blog_id');

            $posts = Blog::where('status', 'Postado')
                ->whereIn('id', $blogCategories)
                ->with('categories')
                ->orderBy('created_at', 'desc')
                ->paginate(9);

            return \view('site.blog.index', \compact('category', 'posts', 'description'));
        } else {
            return view('errors.404');
        }
    }
}
