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
                <a class="btn fadeInDown" data-anime="600" href=".">Veja nossos Cursos</a>
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
                        <a href="">
                            <img src="{{ asset('storage/courses/medium/' . $course->cover) }}" alt="{{ $course->name }}"
                                width="675" height="385">
                            <h3 class="font-1-xl">{{ $course->name }}</h3>
                            <p class="font-2-m color-5">
                                {{ $course->authorsInfo->count() > 0 ? 'Criado por: ' . $course->authorsInfo->pluck('name')->join(', ') : '' }}
                            </p>
                        </a>
                    </li>
                @endforeach
            </ul>
            <a class="btn container" href="">Conheça todos nossos cursos</a>
        </article>
    @else
        <article class="courses-list">
            <h2 class="container font-1-xxl">cursos em breve<span class="color-p2">.</span></h2>
        </article>
    @endif

    {{-- About --}}
    <article class="tecnologic-bg">
        <div class="tecnologic container">
            <div class="tecnologic-content">
                <span class="font-2-l-b color-5">A Resiliência cibernética</span>
                <h2 class="font-1-xxl color-0">começa com uma equipe capacitada<span class="color-p2">.</span>
                </h2>
                <p class="font-2-l color-5">Somos especialistas em capacitar profissionais de cibersegurança.</p>
                <a class="link" href="">Escolha um curso</a>
                <div class="tecnologic-advantages">
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
            <div class="tecnologic-image">
                <img src="{{ asset('img/tecnologic.webp') }}" width="550" height="1105" alt="{{ env('APP_NAME') }}">
            </div>
        </div>
    </article>

    {{-- Team --}}
    <section class="team" aria-label="Nosso Time">
        <h2 class="container font-1-xxl">nossos parceiros<span class="color-p1">.</span></h2>

        <ul>
            <li><img src="./img/parceiros/caravan.svg" alt="Caravan" width="140" height="38"></li>
            <li><img src="./img/parceiros/ranek.svg" alt="Ranek" width="149" height="36"></li>
            <li><img src="./img/parceiros/handel.svg" alt="Handel" width="140" height="50"></li>
            <li><img src="./img/parceiros/dogs.svg" alt="Dogs" width="152" height="39"></li>
            <li><img src="./img/parceiros/lescone.svg" alt="LeScone" width="208" height="41"></li>
            <li><img src="./img/parceiros/flexblog.svg" alt="FlexBlog" width="165" height="38"></li>
            <li><img src="./img/parceiros/wildbeast.svg" alt="Wildbeast" width="196" height="34"></li>
            <li><img src="./img/parceiros/surfbot.svg" alt="Surfbot" width="200" height="49"></li>
        </ul>
    </section>

    <section class="depoimento" aria-label="Depoimento">
        <div>
            <img src="./img/fotos/depoimento.jpg" width="1560" height="1360"
                alt="Pessoa pedalando uma bicicleta Bikcraft">
        </div>
        <div class="depoimento-conteudo">
            <blockquote class="font-1-xl color-p5">
                <p>Pedalar sempre foi a minha paixão, o que o pessoal da Bikcraft fez foi intensificar o meu amor
                    por esta atividade. Recomendo à todos que amo.</p>
            </blockquote>
            <span class="font-1-m-b color-p4">Ana Júlia</span>
        </div>
    </section>

    <article class="seguros-bg">
        <div class="seguros container">
            <h2 class="font-1-xxl color-0">seguros<span class="color-p1">.</span></h2>
            <div class="seguros-item">
                <h3 class="font-1-xl color-6">PRATA</h3>
                <span class="font-1-xl color-0">R$ 199 <span class="font-1-xs color-6">mensal</span></span>
                <ul class="font-2-m color-0">
                    <li>Duas trocas por ano</li>
                    <li>Assistência técnica</li>
                    <li>Suporte 08h às 18h</li>
                    <li>Cobertura estadual</li>
                </ul>
                <a class="botao secundario" href="./orcamento.html?tipo=seguro&produto=prata">Inscreva-se</a>
            </div>

            <div class="seguros-item">
                <h3 class="font-1-xl color-p1">OURO</h3>
                <span class="font-1-xl color-0">R$ 299 <span class="font-1-xs color-6">mensal</span></span>
                <ul class="font-2-m color-0">
                    <li>Cinco trocas por ano</li>
                    <li>Assistência especial</li>
                    <li>Suporte 24h</li>
                    <li>Cobertura nacional</li>
                    <li>Desconto em parceiros</li>
                    <li>Acesso ao Clube Bikcraft</li>
                </ul>
                <a class="botao" href="./orcamento.html?tipo=seguro&produto=ouro">Inscreva-se</a>
            </div>
        </div>
    </article>
@endsection
