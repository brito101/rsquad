@extends('site.master')

@section('content')
    <main>
        <div class="title-bg">
            <div class="title container">
                <p class="font-2-l-b color-5">Cheat Sheet</p>
                <h1 class="font-1-xxl color-0">confira nossos cheats<span class="color-p1">.</span></h1>
            </div>
        </div>
    </main>

    @if ($categories->count() > 0)
        <div class="container cheats">
            <dl>
                @foreach ($categories as $category)
                    @if ($category->cheats->count() > 0)
                        <div>
                            <dt><button class="font-1-m-b" aria-controls="{{ $category->uri }}"
                                    aria-expanded="true">{{ $category->title }}</button></dt>
                            @foreach ($category->cheats as $cheat)
                                <dd class="{{ $category->uri }}" class="font-2-s color-9"><a
                                        href="{{ route('site.cheat-sheets.post', ['uri' => $cheat->uri]) }}">{{ $cheat->title }}</a>
                                </dd>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </dl>
        </div>
    @else
        <div class="posts container">
            <h2 class="font-1-xl color-p2">Em breve... estamos preparando posts de primeira para você :)</h2>
        </div>
    @endif
@endsection
