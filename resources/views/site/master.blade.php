<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@RCBrito101" />
    <meta name="twitter:creator" content="@RCBrito101" />
    <meta name="twitter:domain" content="{{ env('APP_URL') }}" />
    <meta property="article:publisher" content="https://www.facebook.com/RodrigoBritoWebDeveloper" />
    <meta property="article:author" content="https://www.facebook.com/rodrigo.carvalhodebrito" />
    <meta property="fb:app_id" content="550149899141611" />
    <meta itemprop="name" content="{{ env('APP_NAME') }}" />
    <meta itemprop="description" content="{{ env('APP_DESC') }}" />
    <meta itemprop="url" content="{{ env('APP_URL') }}" />
    <meta itemprop="image" content="{{ asset('img/share.png') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" /> @laravelPWA

    <link rel="icon" href="./favicon.svg" type="image/svg+xml">
    <link rel="preload" href="{{ asset('css/site.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">

    <script>
        document.documentElement.classList.add('js');
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    @metas
</head>

<body>
    <header class="header-bg">
        <div class="header container">
            <a href="{{ route('site.home') }}" title="{{ env('APP_NAME') }}">
                <img src="{{ asset('img/logo-100.webp') }}" width="100" height="100" alt="{{ env('APP_NAME') }}">
                <h1>Squady Academy</h1>
            </a>

            <nav aria-label="primaria">
                <ul class="header-menu font-1-m color-0">
                    <li><a href="./bicicletas.html">Bicicletas</a></li>
                    <li><a href="./seguros.html">Seguros</a></li>
                    <li><a href="./contato.html">Contato</a></li>
                </ul>
            </nav>
        </div>
    </header>

    @yield('content')

    <footer class="footer-bg">
        <div class="footer container">
            <img src="./img/bikcraft.svg" width="136" height="32" alt="Bikcraft">
            <div class="footer-contato">
                <h3 class="font-2-l-b color-0">Contato</h3>
                <ul class="font-2-m color-5">
                    <li><a href="tel:+552199999999">+55 21 9999-9999</a></li>
                    <li><a href="mailto:contato@bikcraft.com">contato@bikcraft.com</a></li>
                    <li>Rua Ali Perto, 42 - Botafogo</li>
                    <li>Rio de Janeiro - RJ</li>
                </ul>
                <div class="footer-redes">
                    <a href="./">
                        <img src="./img/redes/instagram.svg" width="32" height="32" alt="Instagram">
                    </a>
                    <a href="./">
                        <img src="./img/redes/facebook.svg" width="32" height="32" alt="Facebook">
                    </a>
                    <a href="./">
                        <img src="./img/redes/youtube.svg" width="32" height="32" alt="Youtube">
                    </a>
                </div>
            </div>
            <div class="footer-informacoes">
                <h3 class="font-2-l-b color-0">Informações</h3>
                <nav>
                    <ul class="font-1-m color-5">
                        <li><a href="./bicicletas.html">Bicicletas</a></li>
                        <li><a href="./seguros.html">Seguros</a></li>
                        <li><a href="./contato.html">Contato</a></li>
                        <li><a href="./termos.html">Termos e Condições</a></li>
                    </ul>
                </nav>
            </div>
            <p class="footer-copy font-2-m color-6">Bikcraft © Alguns direitos reservados.</p>
        </div>
    </footer>

    <script src="./js/plugins/simple-anime.js"></script>
    <script src="./js/script.js"></script>
</body>

</html>