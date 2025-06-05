@extends('site.master')

@section('content')
<main class="about-page-bg">
    <div class="title-bg">
        <div class="title container">
            <p class="font-2-l-b color-5">Nossa História</p>
            <h1 class="font-1-xxl color-0">R<span class="color-p2">Squad Academy</span></h1>
            <p class="font-2-l color-0">Empresa no segmento de Educação e tecnologia voltada a Segurança da informação. 
                Possuímos professores certificados com as principais certificações e com bastante
                experiência no mercado. Nosso foco principal e prover conhecimento nas áreas de:</p>
        </div>
    </div>

    <div class="about container">
        <div class="about-item fadeInLeft" data-anime="200">
            <h3 class="font-1-xl color-6">Blue Team</h3>
            <ul class="font-2-m color-0">
                <li>Resposta a Incidentes</li>
                <li>SOC</li>
                <li>DevSecOPs</li>
                <li>DFIR</li>
                <li>Analise de malware</li>
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
</main>

<article class="advantages-bg">
    <div class="advantages container">
        <h2 class="font-1-xxl color-0">conte conosco<span class="color-p1">.</span></h2>
        <ul>
            <li>
                <img src="{{ asset('img/icons/square.svg') }}" alt="Comece sua jornada">
                <h3 class="font-1-l color-0">Comece sua jornada</h3>
                <p class="font-2-s color-5">O primeiro passo pode mudar seu futuro. Dê início com propósito.</p>
            </li>
            <li>
                <img src="{{ asset('img/icons/carbon.svg') }}" alt="Forjado para liderar">
                <h3 class="font-1-l color-0">Forjado para liderar</h3>
                <p class="font-2-s color-5">Você é parte da nova geração que enfrenta o caos com estratégia e coragem.
                </p>
            </li>
            <li>
                <img src="{{ asset('img/icons/speed.svg') }}" alt="Crescimento contínuo">
                <h3 class="font-1-l color-0">Crescimento contínuo</h3>
                <p class="font-2-s color-5">TCada desafio enfrentado é uma chance real de evoluir e se destacar.</p>
            </li>
            <li>
                <img src="{{ asset('img/icons/tracker.svg') }}" alt="Rastreamento de impacto">
                <h3 class="font-1-l color-0">Rastreamento de impacto</h3>
                <p class="font-2-s color-5">Monitoramos seu progresso para garantir desenvolvimento técnico e
                    estratégico.</p>
            </li>
            <li>
                <img src="{{ asset('img/icons/lock.svg') }}" alt="Ambiente Seguro" />
                <h3 class="font-1-l color-0">Ambiente Seguro</h3>
                <p class="font-2-s color-5">Você aprende errando sem medo. Aqui, falhas viram lições valiosas.</p>
            </li>
            <li>
                <img src="{{ asset('img/icons/clock.svg') }}" alt="Respostas em tempo real">
                <h3 class="font-1-l color-0">Respostas em tempo real</h3>
                <p class="font-2-s color-5">Treinamentos que simulam crises reais com foco em agilidade e precisão.</p>
            </li>
        </ul>
    </div>
</article>

<article class="questions container">
    <h2 class="font-1-xxl">perguntas frequentes<span class="color-p2">.</span></h2>
    <dl>
        <div>
            <dt><button class="font-1-m-b" aria-controls="question1" aria-expanded="true">Qual forma de pagamento vocês
                    aceitam?</button></dt>
            <dd id="question1" class="font-2-s color-9 active">Aceitamos pagamentos parcelados em todos os cartões de
                crédito. Para pagamentos à vista também aceitarmos PIX, boleto ou cartão de crédito.</dd>
        </div>
        <div>
            <dt><button class="font-1-m-b" aria-controls="question2" aria-expanded="false">Como posso entrar em
                    contato?</button></dt>
            <dd id="question2" class="font-2-s color-9">Você pode entrar em contato por meio de nossos canais disponíveis na página de contato.</dd>
        </div>
    </dl>
</article>
@endsection