@extends('site.master')

@section('content')
    <main class="title-bg">
        <div>
            <div class="title container">
                <p class="font-2-xl color-5">
                    @if ($course->is_promotional == 1)
                        {!! '<span style="text-decoration: line-through;" class="font-2-s">R$ ' .
                            number_format($course->price, 2, ',', '.') .
                            '</span> R$ ' .
                            number_format($course->promotional_price, 2, ',', '.') !!}
                    @else
                        {{ 'R$ ' . number_format($course->price, 2, ',', '.') }}
                    @endif
                </p>
                <h1 class="font-1-xxl color-0">{{ $course->name }}<span class="color-p2">.</span></h1>
            </div>
        </div>
        <div class="course container">
            <div class="course-images">
                <img src="{{ $course->cover ? asset('storage/courses/' . $course->cover) : asset('img/share.png') }}"
                    alt="{{ $course->name }}">
            </div>
            <div class="course-content">
                <div class="font-2-l color-5">{!! nl2br($course->description) !!}</div>
                @if ($course->sales_link)
                    <div class="course-buy">
                        <a class="btn" href="{{ $course->sales_link }}" target="_blank">Adquira Agora!</a>
                    </div>
                @endif
                @if ($course->instructorsInfo->count() > 0)
                    <h2 class="font-1-xs color-0">Instrutores</h2>
                    <ul class="course-instructors">
                        @foreach ($course->instructorsInfo as $instructor)
                            <li>
                                <img src="{{ $instructor->photo ? url('storage/users/' . $instructor->photo) : asset('vendor/adminlte/dist/img/avatar.png') }}"
                                    alt="{{ $instructor->name }}">
                                <h3 class="font-1-m color-0">{{ $instructor->name }}</h3>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </main>

    @if ($courses->count() > 0)
        <article class="courses-list">
            <h2 class="container font-1-xxl">Escolha o seu<span class="color-p2">.</span></h2>
            <ul>
                @foreach ($courses as $course)
                    <li>
                        <a href="{{ route('site.courses.show', ['uri' => $course->uri]) }}">
                            <img src="{{ $course->cover ? asset('storage/courses/' . $course->cover) : asset('img/share.png') }}"
                                alt="{{ $course->name }}" width="675" height="385">
                            <h3 class="font-1-xl">{{ $course->name }}</h3>
                            <p class="font-2-m color-p2">
                                {{ $course->price > 0 ? 'R$ ' . number_format($course->price, 2, ',', '.') : '' }}
                            </p>
                        </a>
                    </li>
                @endforeach
            </ul>
            <a class="btn container" href="{{ route('site.courses') }}">Conheça todos nossos cursos</a>
        </article>
    @endif

    <article class="about-bg">
        <div class="about container">
            <div class="about-image">
                <img src="{{ asset('img/soc.webp') }}" alt="Pessoa em um curso de SOC.">
            </div>
            <div class="about-content">
                <h2 class="font-1-xxl color-0">Evolua com a R<span class="color-p2">Squad</span>.</h2>
                <p class="font-2-l color-5">Participe dos nossos cursos e domine a resposta a incidentes com aulas práticas e conteúdo de alto nível.</p>
                <a class="btn" href="{{ route('site.about') }}">Conheça Mais</a>
            </div>
        </div>
    </article>
@endsection
