@extends('site.master')

@section('content')
    {{-- Hero --}}
    <main class="introduction-bg">
        <div class="introduction container">
            <div class="introduction-content">
                <h1 class="font-1-xxl color-0 fadeInDown" data-anime="200">Eleve suas habilidades ao próximo nível<span
                        class="color-p1">.</span></h1>
                <p class="font-2-l color-5 fadeInDown" data-anime="400">Somos especialistas em capacitar profissionais de
                    cibersegurança com o conhecimento e as ferramentas necessárias para enfrentar os desafios mais críticos
                    do setor.</p>
                <a class="btn fadeInDown" data-anime="600" href="{{ route('site.courses') }}">Veja nossos Cursos</a>
            </div>
            <picture data-anime="800" class="fadeInDown">
                <source media="(max-width: 800px)" srcset="{{ asset('img/introduction-min.webp') }}">
                <img src="{{ asset('img/introduction.webp') }}" width="560" height="700"
                    alt="Bicicleta elétrica preta.">
            </picture>
        </div>
    </main>

    {{-- Courses --}}
    @if ($courses->count() > 0)
        <article class="courses-list">
            <h2 class="container font-1-xxl">escolha o seu<span class="color-p2">.</span></h2>
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
    @else
        <article class="courses-list">
            <h2 class="container font-1-xxl">cursos em breve<span class="color-p2">.</span></h2>
        </article>
    @endif

    {{-- About --}}
    <article class="about-home-bg">
        <div class="about-home container">
            <div class="about-content">
                <span class="font-2-l-b color-5">A Resiliência cibernética</span>
                <h2 class="font-1-xxl color-0">começa com uma equipe capacitada<span class="color-p2">.</span>
                </h2>
                <p class="font-2-l color-5">Somos especialistas em capacitar profissionais de cibersegurança.</p>
                <a class="link" href="{{ route('site.courses') }}">Escolha um curso</a>
                <div class="about-advantages">
                    <div>
                        <img src="{{ asset('img/icons/tracker.svg') }}" width="24" height="24" alt="Nossa missão">
                        <h3 class="font-1-m color-0">Nossa missão</h3>
                        <p class="font-2-s color-5">Transformar a maneira como organizações lidam com ameaças,
                            oferecendo serviços especializados e treinamentos de ponta em resposta a incidentes.</p>
                    </div>
                    <div>
                        <img src="{{ asset('img/icons/speed.svg') }}" width="24" height="24" alt="Não há limites">
                        <h3 class="font-1-m color-0">Não há limites</h3>
                        <p class="font-2-s color-5">Seja você um iniciante na área ou um profissional experiente, a Rsquad
                            Academy está aqui para ajudá-lo a evoluir e dominar o cenário de ameaças digitai.</p>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <img src="{{ asset('img/about.webp') }}" width="550" height="1105" alt="{{ env('APP_NAME') }}">
            </div>
        </div>
    </article>

    {{-- Team --}}
    <section class="team" aria-label="Nosso Time">
        <h2 class="container font-1-xxl">nosso time<span class="color-p2">.</span></h2>
        <ul>
            <li><img src="{{ asset('img/team-v1/eric.webp') }}" alt="Eric Fraga" width="400" height="350"></li>
            <li><img src="{{ asset('img/team-v1/wagner.webp') }}" alt="Wagner Souza" width="400" height="350"></li>
            <li><img src="{{ asset('img/team-v1/eddy.webp') }}" alt="Eddy Martins" width="400" height="350"></li>
        </ul>
    </section>

    <section class="phrase" aria-label="Frase">
        <div>
            <img src="{{ asset('img/soc.webp') }}" width="1560" height="1360"
                alt="Quando cada segundo conta, o Blue Team responde sempre primeiro<">
        </div>
        <div class="phrase-content">
            <blockquote class="font-1-xl color-0">
                <p><span class="color-p1">"</span>Quando cada segundo conta, o Blue Team responde sempre primeiro<span
                        class="color-p1">"</span></p>
            </blockquote>
        </div>
    </section>

    <article class="about-home-secondary">
        <div class="about container">
            <h2 class="font-1-xxl color-0">R<span class="color-p2">Squad Academy</span></h2>
            <div class="about-item fadeInLeft" data-anime="200">
                <h3 class="font-1-xl color-6">Blue Team</h3>
                <ul class="font-2-m color-0">
                    <li>Resposta a Incidentes</li>
                    <li>SOC</li>
                    <li>DevSecOPs</li>
                    <li>DFIR</li>
                    <li>Análise de malware</li>
                </ul>
                <a class="btn" href="{{ route('site.courses') }}">Inscreva-se</a>
            </div>

            <div class="about-item fadeInRight" data-anime="400">
                <h3 class="font-1-xl color-p2">Professores certificados</h3>
                <ul class="font-2-m color-0">
                    <li>CEH</li>
                    <li>ECIH</li>
                    <li>SEC+</li>
                    <li>CYSA+</li>
                    <li>Elastic Gold Contribuitor</li>
                    <li>ISO 27001</li>
                    <li>NSE4</li>
                    <li>NSE 7</li>
                    <li>Cloud Security</li>
                    <li>CTIA</li>
                </ul>
            </div>
        </div>
    </article>
@endsection
