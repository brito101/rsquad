@extends('site.master')

@section('content')
    <main class="title-bg">
        <div>
            <div class="title container">
                <p class="font-2-l-b color-5">
                    <a href="{{ route('site.workshops') }}" class="color-5">Workshops</a>
                </p>
                <h1 class="font-1-xxl color-0">{{ $workshop->title }}<span class="color-p2">.</span></h1>
            </div>
        </div>
        <div class="course container">
            <div class="course-images">
                <img src="{{ $workshop->cover ? asset($workshop->cover) : asset('img/share.png') }}"
                    alt="{{ $workshop->title }}">
            </div>
            <div class="course-content" style="width: 100%;">
                @if ($workshop->description)
                    <div class="font-2-l color-5">{!! nl2br($workshop->description) !!}</div>
                @endif

                <div id="course-details">
                    <p class="font-2-m color-5">
                        @if ($workshop->scheduled_at)
                            <i class="fas fa-calendar-alt"></i>
                            {{ $workshop->scheduled_at->format('d/m/Y \à\s H:i') }}
                        @endif

                        @if ($workshop->duration)
                            <i class="fas fa-clock"></i>
                            {{ $workshop->duration }} minutos
                        @endif
                    </p>
                </div>

                @if ($workshop->video_type !== 'none' && $workshop->getVideoEmbedUrl())
                    <div class="video-container" style="margin: 30px 0;">
                        <div
                            style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 4px;">
                            <iframe src="{{ $workshop->getVideoEmbedUrl() }}"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                @endif

                @if ($workshop->content)
                    <div class="font-2-l color-5" style="margin: 30px 0;">
                        {!! $workshop->content !!}
                    </div>
                @endif

                <p class="font-2-s color-8" style="margin-top: 30px;">
                    <strong>Publicado por:</strong> {{ $workshop->user->name }} em
                    {{ $workshop->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </main>

    @if ($relatedWorkshops->count() > 0)
        <article class="courses-list">
            <h2 class="container font-1-xxl">Outros Workshops<span class="color-p2">.</span></h2>
            <ul>
                @foreach ($relatedWorkshops as $related)
                    <li>
                        <a href="{{ route('site.workshops.show', ['slug' => $related->slug]) }}">
                            <img src="{{ $related->cover ? asset($related->cover) : asset('img/share.png') }}"
                                alt="{{ $related->title }}" width="675" height="385">
                            <h3 class="font-1-xl">{{ $related->title }}</h3>
                            @if ($related->duration)
                                <p class="font-2-m color-p2">
                                    <i class="fas fa-clock"></i> {{ $related->duration }} min
                                </p>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
            <a class="btn container" href="{{ route('site.workshops') }}">Ver Todos os Workshops</a>
        </article>
    @endif

    <article class="about-bg">
        <div class="about container">
            <div class="about-image">
                <img src="{{ asset('img/soc.webp') }}" alt="Pessoa em um curso de SOC.">
            </div>
            <div class="about-content">
                <h2 class="font-1-xxl color-0">Evolua com a R<span class="color-p2">Squad</span>.</h2>
                <p class="font-2-l color-5">Participe dos nossos cursos e domine a resposta a incidentes com aulas práticas
                    e conteúdo de alto nível.</p>
                <a class="btn" href="{{ route('site.about') }}">Conheça Mais</a>
            </div>
        </div>
    </article>
@endsection
