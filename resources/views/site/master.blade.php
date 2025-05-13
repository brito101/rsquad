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
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        {{-- @laravelPWA --}}
        <link rel="icon" href="{{ asset('img/logo-rounded.png') }}" type="image/png">
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
                    <img src="{{ asset('img/brand.webp') }}" width="221" height="100"
                        alt="{{ env('APP_NAME') }}">
                </a>

                <nav aria-label="primaria">
                    <ul class="header-menu font-1-m color-0">
                        <li><a href="x">Cursos</a></li>
                        <li><a href="y">Blog</a></li>
                        <li><a href="z">Cheat Sheet</a></li>
                        <li><a href="ç">Contato</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        @yield('content')

        <footer class="footer-bg">
            <div class="footer container">
                <img src="{{ asset('img/brand.webp') }}" width="221" height="100" alt="{{ env('APP_NAME') }}">
                <div class="footer-contact">
                    <h3 class="font-2-l-b color-0">Contato</h3>
                    <ul class="font-2-m color-5">
                        <li><a href="tel:+558198351988">+55 81 9835-1988</a></li>
                        <li><a href="mailto:contato@rsquadacademy.com.br">contato@rsquadacademy.com.br</a></li>
                        <li>Recife - PE</li>
                    </ul>
                    <div class="footer-social">
                        <a href="https://www.linkedin.com/company/rsquadacademy" target="_blank" rel="noopener"
                            title="LinkedIn">
                            <img src="{{ asset('img/social/linkedin.svg') }}" width="32" height="32"
                                alt="LinkedIn">
                        </a>
                        <a href="https://instagram.com/rsquad.academy" target="_blank" rel="noopener" title="Instagram">
                            <img src="{{ asset('img/social/instagram.svg') }}" width="32" height="32"
                                alt="Instagram">
                        </a>
                        <a href="https://www.youtube.com/@RSquadAcademy" target="_blank" rel="noopener" title="Youtube">
                            <img src="{{ asset('img/social/youtube.svg') }}" width="32" height="32"
                                alt="Youtube">
                        </a>
                    </div>
                </div>
                <div class="footer-informations">
                    <h3 class="font-2-l-b color-0">Informações</h3>
                    <nav>
                        <ul class="font-1-m color-5">
                            <li><a href="">Cursos</a></li>
                            <li><a href="">Blog</a></li>
                            <li><a href="">Cheat Sheet</a></li>
                            <li><a href="">Contato</a></li>
                            <li><a href="{{ route('site.terms') }}">Termos e Condições</a></li>
                        </ul>
                    </nav>
                </div>
                <p class="footer-copy font-2-m color-6">{{ env('APP_NAME') }} © Todos direitos reservados.</p>
            </div>
        </footer>

        <script src="{{ asset('js/simple-anime.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>
    </body>

</html>
