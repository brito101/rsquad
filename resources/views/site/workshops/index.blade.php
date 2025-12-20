@extends('site.master')

@section('content')
    <main>
        <div class="title-bg">
            <div class="title container">
                <p class="font-2-l-b color-5">Conhecimento e prática</p>
                <h1 class="font-1-xxl color-0">Workshops<span class="color-p2">.</span></h1>
            </div>
        </div>
        
        @if ($workshops->count() > 0)
            @foreach ($workshops as $workshop)
                @if ($loop->index % 2 == 0)
                    <div>
                        <div class="courses container">
                            <div class="courses-image">
                                <img src="{{ $workshop->cover ? asset($workshop->cover) : asset('img/share.png') }}"
                                    alt="{{ $workshop->title }}">
                                @if($workshop->duration)
                                    <span class="font-2-m color-0">{{ $workshop->duration }} min</span>
                                @endif
                            </div>
                            <div class="courses-content">
                                <h2 class="font-1-xl">{{ $workshop->title }}</h2>
                                @if($workshop->description)
                                    <p class="font-2-l color-8">{{ Str::limit($workshop->description, 150) }}</p>
                                @endif
                                @if($workshop->scheduled_at)
                                    <ul class="font-1-m color-8">
                                        <li>
                                            <img src="{{ asset('img/icons/tracker.svg') }}" alt="">
                                            {{ $workshop->scheduled_at->format('d/m/Y \à\s H:i') }}
                                        </li>
                                    </ul>
                                @endif
                                <a class="btn arrow" href="{{ route('site.workshops.show', ['slug' => $workshop->slug]) }}">
                                    Saiba mais
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="courses-bg">
                        <div class="courses container">
                            <div class="courses-image">
                                <img src="{{ $workshop->cover ? asset($workshop->cover) : asset('img/share.png') }}"
                                    alt="{{ $workshop->title }}">
                                @if($workshop->duration)
                                    <span class="font-2-m color-0">{{ $workshop->duration }} min</span>
                                @endif
                            </div>
                            <div class="courses-content">
                                <h2 class="font-1-xl color-0">{{ $workshop->title }}</h2>
                                @if($workshop->description)
                                    <p class="font-2-l color-2">{{ Str::limit($workshop->description, 150) }}</p>
                                @endif
                                @if($workshop->scheduled_at)
                                    <ul class="font-1-m color-2">
                                        <li>
                                            <img src="{{ asset('img/icons/tracker.svg') }}" alt="">
                                            {{ $workshop->scheduled_at->format('d/m/Y \à\s H:i') }}
                                        </li>
                                    </ul>
                                @endif
                                <a class="btn arrow" href="{{ route('site.workshops.show', ['slug' => $workshop->slug]) }}">
                                    Saiba mais
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="courses container">
                <h2 class="font-1-xl color-p2">Em breve... estamos preparando workshops incríveis para você :)</h2>
            </div>
        @endif
    </main>
@endsection
