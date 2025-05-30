@extends('site.master')

@section('content')
    <main>
        <div class="title-bg">
            <div class="title container">
                <p class="font-2-l-b color-5">Blog</p>
                <h1 class="font-1-xxl color-0">confira nossos
                    posts{{ isset($category) ? ' em: ' . $category->title : '' }}<span class="color-p2">.</span></h1>
            </div>
        </div>
        @if ($posts->count() > 0)
            @foreach ($posts as $post)
                @if ($loop->index % 2 == 0)
                    <div class="posts container">
                        <div class="posts-image">
                            <img src="{{ $post->cover ? asset('storage/blog/medium/' . $post->cover) : asset('img/share.png') }}"
                                alt="{{ $post->title }}">
                        </div>
                        <div class="posts-content">
                            <h2 class="font-1-xl">{{ $post->title }}</h2>
                            <h3 class="font-1-l color-p4">{{ $post->subtitle }}</h3>
                            @if ($post->categories->count() > 0)
                                <p class="font-1-xs color-8">Categorias:</p>
                                <ul>
                                    @foreach ($post->categories as $category)
                                        <li>
                                            <a class="btn"
                                                href="{{ route('site.blog.category', ['category' => $category->category->uri]) }}">
                                                {{ $category->category->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <a class="btn arrow" href="{{ route('site.blog.post', ['uri' => $post->uri]) }}">Leia
                                mais!</a>
                        </div>
                    </div>
                @else
                    <div class="posts-bg">
                        <div class="posts container">
                            <div class="posts-image">
                                <img src="{{ $post->cover ? asset('storage/blog/medium/' . $post->cover) : asset('img/share.png') }}"
                                    alt="{{ $post->title }}">
                            </div>
                            <div class="posts-content">
                                <h2 class="font-1-xl color-0">{{ $post->title }}</h2>
                                <h3 class="font-1-l color-5">{{ $post->subtitle }}</h3>
                                @if ($post->categories->count() > 0)
                                    <p class="font-1-xs color-8">Categorias:</p>
                                    <ul class="font-1-m color-2">
                                        @foreach ($post->categories as $category)
                                            <li>
                                                <a class="btn"
                                                    href="{{ route('site.blog.category', ['category' => $category->category->uri]) }}">
                                                    {{ $category->category->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <a class="btn arrow" href="{{ route('site.blog.post', ['uri' => $post->uri]) }}">Leia
                                    mais!</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            <div class="container paginate">{{ $posts->links() }}</div>
        @else
            <div class="posts container">
                <h2 class="font-1-xl color-p2">Em breve... estamos preparando posts de primeira para você :)</h2>
            </div>
        @endif
    </main>

@endsection
