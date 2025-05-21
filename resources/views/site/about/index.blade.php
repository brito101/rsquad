@extends('site.master')

@section('content')
    <main class="about-page-bg">
        <div class="title-bg">
            <div class="title container">
                <p class="font-2-l-b color-5">Nossa História</p>
                <h1 class="font-1-xxl color-0">R<span class="color-p2">Squadsail Academy</span></h1>
                <p class="font-2-l color-0">nasceu da necessidade de formar
                    profissionais realmente prontos para atuar na linha de frente da segurança cibernética. Fundada por
                    especialistas que viveram operações reais, nossa missão é simples:</p>
            </div>
        </div>

        <div class="about container">
            <div class="about-item fadeInLeft" data-anime="200">
                <h3 class="font-1-xl color-6">Capacitação</h3>
                <ul class="font-2-m color-0">
                    <li>Proteja organizações</li>
                    <li>Tenha resiliência cibernética</li>
                    <li>Aprofunde habilidades práticas</li>
                    <li>Domine as ferramentas do mundo real</li>
                </ul>
                <a class="btn secondary" href="{{ route('site.courses') }}">Inscreva-se</a>
            </div>

            <div class="about-item fadeInRight" data-anime="400">
                <h3 class="font-1-xl color-p1">Experiências práticas</h3>
                <ul class="font-2-m color-0">
                    <li>Conteúdo direto ao ponto</li>
                    <li>Conhecimento aplicado</li>
                    <li>Comunidade engajada</li>
                </ul>
                <a class="btn" href="{{ route('site.courses') }}">Evolua!</a>
            </div>
        </div>
    </main>

    <article class="advantages-bg">
        <div class="advantages container">
            <h2 class="font-1-xxl color-0">vantagens<span class="color-p1">.</span></h2>
            <ul>
                <li>
                    <img src="./img/icones/eletrica.svg" alt="">
                    <h3 class="font-1-l color-0">Todo especialista começa com o primeiro passo</h3>
                    <p class="font-2-s color-5">Talento certo, com a preparação certa, pode transformar o jogo</p>
                </li>
                <li>
                    <img src="./img/icones/carbono.svg" alt="">
                    <h3 class="font-1-l color-0">Você nasceu para liderar</h3>
                    <p class="font-2-s color-5">Supere desafios reais e torne-se referência em cibersegurança..</p>
                </li>
                <li>
                    <img src="./img/icones/sustentavel.svg" alt="">
                    <h3 class="font-1-l color-0">Sustentável</h3>
                    <p class="font-2-s color-5">Trabalhamos com a filosofia de desperdício zero. Qualquer parte defeituosa é
                        reciclada e reutilizada em outro projeto.</p>
                </li>
                <li>
                    <img src="./img/icones/rastreador.svg" alt="">
                    <h3 class="font-1-l color-0">Rastreador</h3>
                    <p class="font-2-s color-5">Utilizamos o GPS da sua Bikcraft em conjunto com especialistas em segurança
                        para efetuarmos a recuperação.</p>
                </li>
                <li>
                    <img src="./img/icones/seguro.svg" alt="">
                    <h3 class="font-1-l color-0">Segurança</h3>
                    <p class="font-2-s color-5">Com o seguro Bikcraft você pode ficar tranquilo em saber que o seu dinheiro
                        não será perdido em casos de roubo.</p>
                </li>
                <li>
                    <img src="./img/icones/velocidade.svg" alt="">
                    <h3 class="font-1-l color-0">Rapidez</h3>
                    <p class="font-2-s color-5">A sua Bikcraft ficará pronta para uso no mesmo dia, caso você traga ela para
                        ser reparada em uma das filiais.</p>
                </li>
            </ul>
        </div>
    </article>

    <article class="perguntas container">
        <h2 class="font-1-xxl">perguntas frequentes<span class="color-p1">.</span></h2>
        <dl>
            <div>
                <dt><button class="font-1-m-b" aria-controls="pergunta1" aria-expanded="true">Qual forma de pagamento vocês
                        aceitam?</button></dt>
                <dd id="pergunta1" class="font-2-s color-9 ativa">Aceitamos pagamentos parcelados em todos os cartões de
                    crédito. Para pagamentos à vista também aceitarmos PIX e Boleto através do PagSeguro.</dd>
            </div>
            <div>
                <dt><button class="font-1-m-b" aria-controls="pergunta2" aria-expanded="false">Como posso entrar em
                        contato?</button></dt>
                <dd id="pergunta2" class="font-2-s color-9">Aceitamos pagamentos parcelados em todos os cartões de crédito.
                    Para pagamentos à vista também aceitarmos PIX e Boleto através do PagSeguro.</dd>
            </div>
            <div>
                <dt><button class="font-1-m-b" aria-controls="pergunta3" aria-expanded="false">Vocês possuem algum
                        desconto?</button></dt>
                <dd id="pergunta3" class="font-2-s color-9">Aceitamos pagamentos parcelados em todos os cartões de crédito.
                    Para pagamentos à vista também aceitarmos PIX e Boleto através do PagSeguro.</dd>
            </div>
            <div>
                <dt><button class="font-1-m-b" aria-controls="pergunta4" aria-expanded="false">Qual a garantia que
                        possuo?</button></dt>
                <dd id="pergunta4" class="font-2-s color-9">Aceitamos pagamentos parcelados em todos os cartões de crédito.
                    Para pagamentos à vista também aceitarmos PIX e Boleto através do PagSeguro.</dd>
            </div>
            <div>
                <dt><button class="font-1-m-b" aria-controls="pergunta5" aria-expanded="false">Posso parcelar no
                        boleto?</button></dt>
                <dd id="pergunta5" class="font-2-s color-9">Aceitamos pagamentos parcelados em todos os cartões de crédito.
                    Para pagamentos à vista também aceitarmos PIX e Boleto através do PagSeguro.</dd>
            </div>
            <div>
                <dt><button class="font-1-m-b" aria-controls="pergunta6" aria-expanded="false">Quantas trocas posso fazer ao
                        ano?</button></dt>
                <dd id="pergunta6" class="font-2-s color-9">Aceitamos pagamentos parcelados em todos os cartões de crédito.
                    Para pagamentos à vista também aceitarmos PIX e Boleto através do PagSeguro.</dd>
            </div>
        </dl>
    </article>
@endsection
