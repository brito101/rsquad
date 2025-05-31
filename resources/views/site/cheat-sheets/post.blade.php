@extends('site.master')

@section('content')
    <main class="post-cheat-bg">
        <div class="title-bg">
            <div class="title container">
                <h1 class="font-1-xxl color-0">{{ $post->title }}</h1>
                <p class="font-2-l-b color-p2">{{ $post->category->title }}</p>
            </div>
        </div>
    </main>

    <section class="container">
        <div class="html_content_cheat">
            {!! $post->content !!}
        </div>
    </section>
@endsection
