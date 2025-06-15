@extends('site.master')

@section('content')
    <main class="post-page-bg">
        <div class="title-bg">
            <div class="title container">
                <h1 class="font-1-xxl color-0">{{ $post->title }}</h1>
                <img src="{{ $post->cover ? asset('storage/blog/' . $post->cover) : asset('img/share.png') }}"
                    alt="{{ $post->title }}">
                <p class="font-2-l-b color-p2">{{ $post->subtitle }}</p>
            </div>
        </div>
    </main>

    <section class="container">
        <div class="html_content">
            {!! $post->content !!}
        </div>
    </section>

    @if ($related->count() > 0)
        <section class="related">
            <div class="title-bg">
                <div class="container">
                    <h2 class="font-1-xxl color-0">Artigos relacionados<span class="color-p1">.</span></h2>
                </div>
            </div>
            @foreach ($related as $post)
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
            @endforeach
        </section>
    @endif
@endsection
